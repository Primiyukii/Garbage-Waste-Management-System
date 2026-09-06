<?php

namespace App\Observers;

use App\Models\Customer;
use App\Services\AuditService;

class CustomerObserver
{
    public function __construct(private AuditService $audit)
    {
    }

    public function created(Customer $customer): void
    {
        $this->audit->log('customer.created', "Created customer {$customer->customer_code}", $customer);
    }

    public function updated(Customer $customer): void
    {
        $changedFields = array_diff(array_keys($customer->getChanges()), ['updated_at']);

        if (empty($changedFields)) {
            return;
        }

        $changes = implode(', ', $changedFields);
        $this->audit->log('customer.updated', "Updated customer {$customer->customer_code} ({$changes})", $customer);
    }

    public function deleted(Customer $customer): void
    {
        $this->audit->log('customer.deleted', "Deleted customer {$customer->customer_code}", $customer);
    }
}
