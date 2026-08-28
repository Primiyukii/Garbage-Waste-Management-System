<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCollectorRequest;
use App\Http\Requests\Admin\UpdateCollectorRequest;
use App\Models\Barangay;
use App\Models\Collector;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CollectorController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Collector::class);

        $collectors = Collector::query()
            ->with(['user', 'areas'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->where('employee_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $status = $request->string('status');
                $query->whereHas('user', fn ($q) => $q->where('status', $status));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.collectors.index', compact('collectors'));
    }

    public function create(): View
    {
        $this->authorize('create', Collector::class);

        $barangays = Barangay::with('city')->orderBy('name')->get();

        return view('admin.collectors.create', compact('barangays'));
    }

    public function store(StoreCollectorRequest $request): RedirectResponse
    {
        $this->authorize('create', Collector::class);

        $validated = $request->validated();

        $photoPath = $request->hasFile('photo')
            ? $request->file('photo')->store('collectors', 'public')
            : null;

        $collector = DB::transaction(function () use ($validated, $photoPath) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
                'role_id' => Role::where('slug', 'collector')->first()->id,
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            $collector = Collector::create([
                'user_id' => $user->id,
                'employee_id' => $validated['employee_id'],
                'date_hired' => $validated['date_hired'],
                'photo' => $photoPath,
            ]);

            $collector->areas()->sync($validated['areas'] ?? []);

            return $collector;
        });

        return redirect()->route('admin.collectors.show', $collector)
            ->with('success', 'Collector created successfully.');
    }

    public function show(Collector $collector): View
    {
        $this->authorize('view', $collector);

        $collector->load(['user', 'areas.city']);

        $performance = [
            'assigned' => $collector->collectionSchedules()->count(),
            'completed' => $collector->collectionSchedules()->where('status', 'completed')->count(),
            'missed' => $collector->collectionSchedules()->where('status', 'missed')->count(),
            'cancelled' => $collector->collectionSchedules()->where('status', 'cancelled')->count(),
            'total_waste_kg' => $collector->collectionRecords()->sum('weight_kg'),
        ];

        $recentRecords = $collector->collectionRecords()->with('customer.user')->latest('collection_date')->limit(10)->get();

        return view('admin.collectors.show', compact('collector', 'performance', 'recentRecords'));
    }

    public function edit(Collector $collector): View
    {
        $this->authorize('update', $collector);

        $collector->load(['user', 'areas']);
        $barangays = Barangay::with('city')->orderBy('name')->get();

        return view('admin.collectors.edit', compact('collector', 'barangays'));
    }

    public function update(UpdateCollectorRequest $request, Collector $collector): RedirectResponse
    {
        $this->authorize('update', $collector);

        $validated = $request->validated();

        $photoPath = $collector->photo;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('collectors', 'public');
        }

        DB::transaction(function () use ($validated, $collector, $photoPath) {
            $collector->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
            ]);

            $collector->update([
                'employee_id' => $validated['employee_id'],
                'date_hired' => $validated['date_hired'],
                'photo' => $photoPath,
            ]);

            $collector->areas()->sync($validated['areas'] ?? []);
        });

        return redirect()->route('admin.collectors.show', $collector)
            ->with('success', 'Collector updated successfully.');
    }

    public function destroy(Collector $collector): RedirectResponse
    {
        $this->authorize('delete', $collector);

        $collector->delete();

        return redirect()->route('admin.collectors.index')
            ->with('success', 'Collector deleted successfully.');
    }

    public function toggleStatus(Collector $collector): RedirectResponse
    {
        $this->authorize('update', $collector);

        $collector->user->update([
            'status' => $collector->user->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Collector status updated.');
    }
}
