<?php

namespace App\Policies;

use App\Models\Bill;
use App\Models\User;

class BillPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isCustomer();
    }

    public function view(User $user, Bill $bill): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->customer?->id === $bill->customer_id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Bill $bill): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Bill $bill): bool
    {
        return $user->isAdmin();
    }
}
