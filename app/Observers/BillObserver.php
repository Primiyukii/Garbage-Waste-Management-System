<?php

namespace App\Observers;

use App\Models\Bill;
use App\Services\AuditService;

class BillObserver
{
    public function __construct(private AuditService $audit)
    {
    }

    public function created(Bill $bill): void
    {
        $this->audit->log('bill.created', "Generated bill {$bill->bill_number} for ₱" . number_format($bill->total_amount, 2), $bill);
    }

    public function updated(Bill $bill): void
    {
        if ($bill->wasChanged('status')) {
            $this->audit->log('bill.status_changed', "Bill {$bill->bill_number} status changed to {$bill->status}", $bill);
        }
    }
}
