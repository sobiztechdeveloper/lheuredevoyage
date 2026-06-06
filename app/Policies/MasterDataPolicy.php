<?php

namespace App\Policies;

use App\Models\Master\MasterDataModel;
use App\Models\User;
use App\Policies\Concerns\GrantsAdminsFullAccess;

class MasterDataPolicy
{
    use GrantsAdminsFullAccess;

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
