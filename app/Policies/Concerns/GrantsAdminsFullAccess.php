<?php

namespace App\Policies\Concerns;

use App\Models\Admin;

trait GrantsAdminsFullAccess
{
    public function before($user, string $ability): ?bool
    {
        return $user instanceof Admin ? true : null;
    }
}
