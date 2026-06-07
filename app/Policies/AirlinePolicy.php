<?php

namespace App\Policies;

use App\Models\Airline;
use App\Models\User;
use App\Policies\Concerns\GrantsAdminsFullAccess;

class AirlinePolicy
{
    use GrantsAdminsFullAccess;

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Airline $airline): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Airline $airline): bool
    {
        return false;
    }

    public function delete(User $user, Airline $airline): bool
    {
        return false;
    }

    public function restore(User $user, Airline $airline): bool
    {
        return false;
    }
}
