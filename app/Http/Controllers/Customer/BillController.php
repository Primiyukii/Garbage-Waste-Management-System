<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use Illuminate\View\View;

class BillController extends Controller
{
    public function index(): View
    {
        $customer = auth()->user()->customer;

        $bills = $customer->bills()
            ->with('items')
            ->latest('due_date')
            ->paginate(10);

        return view('customer.bills.index', compact('bills'));
    }

    public function show(Bill $bill): View
    {
        $this->authorize('view', $bill);

        $bill->load(['items', 'payments']);

        return view('customer.bills.show', compact('bill'));
    }
}
