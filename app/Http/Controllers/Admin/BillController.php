<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Customer;
use App\Services\BillingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BillController extends Controller
{
    public function __construct(private BillingService $billingService)
    {
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Bill::class);

        $bills = Bill::query()
            ->with('customer.user')
            ->when($request->filled('search'), fn ($q) => $q->where('bill_number', 'like', '%' . $request->string('search') . '%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest('due_date')
            ->paginate(15)
            ->withQueryString();

        return view('admin.bills.index', compact('bills'));
    }

    public function create(): View
    {
        $this->authorize('create', Bill::class);

        $customers = Customer::with('user')->whereNotNull('service_id')->get();

        return view('admin.bills.create', compact('customers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Bill::class);

        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date', 'after_or_equal:period_start'],
        ]);

        $customer = Customer::findOrFail($validated['customer_id']);

        try {
            $bill = $this->billingService->generateForCustomer(
                $customer,
                Carbon::parse($validated['period_start']),
                Carbon::parse($validated['period_end'])
            );
        } catch (\RuntimeException $e) {
            return back()->withErrors(['customer_id' => $e->getMessage()])->withInput();
        }

        return redirect()->route('admin.bills.show', $bill)->with('success', 'Bill generated successfully.');
    }

    public function show(Bill $bill): View
    {
        $this->authorize('view', $bill);

        $bill->load(['customer.user', 'items', 'payments']);

        return view('admin.bills.show', compact('bill'));
    }
}
