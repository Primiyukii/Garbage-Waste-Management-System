<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(): View
    {
        $customer = auth()->user()->customer;

        $payments = $customer->payments()->with(['bill', 'receipt'])->latest('paid_at')->paginate(10);

        return view('customer.payments.index', compact('payments'));
    }
}
