<?php

namespace App\Observers;

use App\Models\Payment;
use App\Services\AuditService;

class PaymentObserver
{
    public function __construct(private AuditService $audit)
    {
    }

    public function created(Payment $payment): void
    {
        $this->audit->log('payment.recorded', "Recorded payment {$payment->payment_reference} for ₱" . number_format($payment->amount, 2), $payment);
    }
}
