<?php

namespace App\Policies;

use App\Models\CruiseLine;
use App\Models\User;
use App\Policies\Concerns\GrantsAdminsFullAccess;

class CruiseLinePolicy
{
    use GrantsAdminsFullAccess;

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, CruiseLine $line): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, CruiseLine $line): bool
    {
        return false;
    }

    public function delete(User $user, CruiseLine $line): bool
    {
        return false;
    }

    public function restore(User $user, CruiseLine $line): bool
    {
        return false;
    }
}
