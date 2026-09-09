<?php

namespace App\Observers;

use App\Models\User;
use App\Services\AuditService;

class UserObserver
{
    public function __construct(private AuditService $audit)
    {
    }

    public function updated(User $user): void
    {
        $excluded = ['updated_at', 'remember_token', 'last_login_at', 'password', 'email_verified_at'];
        $changedFields = array_diff(array_keys($user->getChanges()), $excluded);

        if (empty($changedFields)) {
            return;
        }

        $changes = implode(', ', $changedFields);
        $this->audit->log('user.updated', "Updated user {$user->name} ({$changes})", $user);
    }
}
