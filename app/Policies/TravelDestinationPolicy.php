<?php

namespace App\Policies;

use App\Models\TravelDestination;
use App\Models\User;
use App\Policies\Concerns\GrantsAdminsFullAccess;

class TravelDestinationPolicy
{
    use GrantsAdminsFullAccess;

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, TravelDestination $destination): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, TravelDestination $destination): bool
    {
        return false;
    }

    public function delete(User $user, TravelDestination $destination): bool
    {
        return false;
    }

    public function restore(User $user, TravelDestination $destination): bool
    {
        return false;
    }

    public function import(User $user): bool
    {
        return false;
    }
}
