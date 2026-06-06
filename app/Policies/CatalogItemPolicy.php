<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\GrantsAdminsFullAccess;
use Illuminate\Database\Eloquent\Model;

class CatalogItemPolicy
{
    use GrantsAdminsFullAccess;

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Model $item): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Model $item): bool
    {
        return false;
    }

    public function delete(User $user, Model $item): bool
    {
        return false;
    }
}
