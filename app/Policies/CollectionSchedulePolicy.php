<?php

namespace App\Policies;

use App\Models\CollectionSchedule;
use App\Models\User;

class CollectionSchedulePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, CollectionSchedule $schedule): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isCollector()) {
            return $user->collector?->id === $schedule->collector_id;
        }

        return $user->customer?->id === $schedule->customer_id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, CollectionSchedule $schedule): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isCollector() && $user->collector?->id === $schedule->collector_id;
    }

    public function delete(User $user, CollectionSchedule $schedule): bool
    {
        return $user->isAdmin();
    }
}
