<?php

namespace App\Policies;

use App\Models\User;

class LocationPolicy
{
    public function manage(User $user): bool
    {
        return $user->isAdmin();
    }
}
