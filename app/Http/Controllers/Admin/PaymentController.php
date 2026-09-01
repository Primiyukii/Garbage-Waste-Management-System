<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePaymentRequest;
use App\Models\Bill;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService)
    {
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Payment::class);

        $payments = Payment::query()
            ->with(['customer.user', 'bill'])
            ->when($request->filled('search'), fn ($q) => $q->where('payment_reference', 'like', '%' . $request->string('search') . '%'))
            ->when($request->filled('method'), fn ($q) => $q->where('method', $request->string('method')))
            ->latest('paid_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Payment::class);

        $bills = Bill::with('customer.user')
            ->whereIn('status', ['unpaid', 'partially_paid', 'overdue'])
            ->get();

        return view('admin.payments.create', compact('bills'));
    }

    public function store(StorePaymentRequest $request): RedirectResponse
    {
        $this->authorize('create', Payment::class);

        $bill = Bill::findOrFail($request->validated('bill_id'));
        $payment = $this->paymentService->record($bill, $request->validated());

        return redirect()->route('admin.payments.show', $payment)->with('success', 'Payment recorded successfully.');
    }

    public function show(Payment $payment): View
    {
        $this->authorize('view', $payment);

        $payment->load(['bill', 'customer.user', 'receipt']);

        return view('admin.payments.show', compact('payment'));
    }
}
