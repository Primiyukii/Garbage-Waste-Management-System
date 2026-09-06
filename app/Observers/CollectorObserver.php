<?php

namespace App\Observers;

use App\Models\Collector;
use App\Services\AuditService;

class CollectorObserver
{
    public function __construct(private AuditService $audit)
    {
    }

    public function created(Collector $collector): void
    {
        $this->audit->log('collector.created', "Created collector {$collector->employee_id}", $collector);
    }

    public function updated(Collector $collector): void
    {
        $changedFields = array_diff(array_keys($collector->getChanges()), ['updated_at']);

        if (empty($changedFields)) {
            return;
        }

        $changes = implode(', ', $changedFields);
        $this->audit->log('collector.updated', "Updated collector {$collector->employee_id} ({$changes})", $collector);
    }

    public function deleted(Collector $collector): void
    {
        $this->audit->log('collector.deleted', "Deleted collector {$collector->employee_id}", $collector);
    }
}
