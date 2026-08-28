<?php

namespace App\Policies;

use App\Models\Collector;
use App\Models\User;

class CollectorPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Collector $collector): bool
    {
        return $user->isAdmin() || $user->id === $collector->user_id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Collector $collector): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Collector $collector): bool
    {
        return $user->isAdmin();
    }
}
