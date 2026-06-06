<?php

namespace App\Policies;

use App\Models\LegalPage;
use App\Models\User;
use App\Policies\Concerns\GrantsAdminsFullAccess;

class LegalPagePolicy
{
    use GrantsAdminsFullAccess;

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, LegalPage $legalPage): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, LegalPage $legalPage): bool
    {
        return false;
    }

    public function delete(User $user, LegalPage $legalPage): bool
    {
        return false;
    }

    public function restore(User $user, LegalPage $legalPage): bool
    {
        return false;
    }

    public function forceDelete(User $user, LegalPage $legalPage): bool
    {
        return false;
    }
}
