<?php

namespace App\Policies;

use App\Models\Master\MasterDataModel;
use App\Models\User;

class MasterDataPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, MasterDataModel $model): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, MasterDataModel $model): bool
    {
        return false;
    }

    public function delete(User $user, MasterDataModel $model): bool
    {
        return false;
    }

    public function restore(User $user, MasterDataModel $model): bool
    {
        return false;
    }
}
