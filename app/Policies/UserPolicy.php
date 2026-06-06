<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\GrantsAdminsFullAccess;

class UserPolicy
{
    use GrantsAdminsFullAccess;

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, User $model): bool
    {
        return false;
    }

    public function update(User $user, User $model): bool
    {
        return false;
    }
}
