<?php

namespace App\Observers;

use App\Models\CollectionSchedule;
use App\Services\AuditService;

class CollectionScheduleObserver
{
    public function __construct(private AuditService $audit)
    {
    }

    public function created(CollectionSchedule $schedule): void
    {
        $this->audit->log('schedule.created', "Created schedule #{$schedule->id} for customer #{$schedule->customer_id}", $schedule);
    }

    public function updated(CollectionSchedule $schedule): void
    {
        if ($schedule->wasChanged('status')) {
            $this->audit->log('schedule.status_changed', "Schedule #{$schedule->id} status changed to {$schedule->status}", $schedule);
        }

        if ($schedule->wasChanged('collector_id')) {
            $this->audit->log('schedule.collector_assigned', "Schedule #{$schedule->id} assigned to collector #{$schedule->collector_id}", $schedule);
        }
    }
}
