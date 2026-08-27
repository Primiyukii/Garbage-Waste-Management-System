<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCustomerRequest;
use App\Http\Requests\Admin\UpdateCustomerRequest;
use App\Models\Barangay;
use App\Models\Customer;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Customer::class);

        $customers = Customer::query()
            ->with(['user', 'barangay'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $status = $request->string('status');
                $query->whereHas('user', fn ($q) => $q->where('status', $status));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function create(): View
    {
        $this->authorize('create', Customer::class);

        $barangays = Barangay::with('city')->orderBy('name')->get();
        $services = Service::where('status', 'active')->orderBy('name')->get();

        return view('admin.customers.create', compact('barangays', 'services'));
    }

    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        $this->authorize('create', Customer::class);

        $validated = $request->validated();

        $customer = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
                'role_id' => Role::where('slug', 'customer')->first()->id,
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            return Customer::create([
                'user_id' => $user->id,
                'customer_code' => $this->generateCustomerCode(),
                'barangay_id' => $validated['barangay_id'],
                'street' => $validated['street'],
                'house_number' => $validated['house_number'],
                'postal_code' => $validated['postal_code'] ?? null,
                'service_id' => $validated['service_id'] ?? null,
                'registered_at' => now(),
            ]);
        });

        return redirect()->route('admin.customers.show', $customer)
            ->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer): View
    {
        $this->authorize('view', $customer);

        $customer->load(['user', 'barangay.city', 'service']);

        $schedules = $customer->collectionSchedules()->with('wasteType')->latest('scheduled_date')->limit(10)->get();
        $bills = $customer->bills()->latest()->limit(10)->get();
        $payments = $customer->payments()->latest()->limit(10)->get();

        return view('admin.customers.show', compact('customer', 'schedules', 'bills', 'payments'));
    }

    public function edit(Customer $customer): View
    {
        $this->authorize('update', $customer);

        $customer->load('user');
        $barangays = Barangay::with('city')->orderBy('name')->get();
        $services = Service::where('status', 'active')->orderBy('name')->get();

        return view('admin.customers.edit', compact('customer', 'barangays', 'services'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse
    {
        $this->authorize('update', $customer);

        $validated = $request->validated();

        DB::transaction(function () use ($validated, $customer) {
            $customer->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
            ]);

            $customer->update([
                'barangay_id' => $validated['barangay_id'],
                'street' => $validated['street'],
                'house_number' => $validated['house_number'],
                'postal_code' => $validated['postal_code'] ?? null,
                'service_id' => $validated['service_id'] ?? null,
            ]);
        });

        return redirect()->route('admin.customers.show', $customer)
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $this->authorize('delete', $customer);

        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    public function toggleStatus(Customer $customer): RedirectResponse
    {
        $this->authorize('update', $customer);

        $customer->user->update([
            'status' => $customer->user->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Customer status updated.');
    }

    private function generateCustomerCode(): string
    {
        $last = Customer::withTrashed()->orderByDesc('id')->first();
        $nextId = $last ? $last->id + 1 : 1;

        return 'CUST-' . str_pad((string) $nextId, 6, '0', STR_PAD_LEFT);
    }
}
