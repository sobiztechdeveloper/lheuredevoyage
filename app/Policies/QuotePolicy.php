<?php

namespace App\Policies;

use App\Models\Quote;
use App\Models\User;
use App\Policies\Concerns\GrantsAdminsFullAccess;

class QuotePolicy
{
    use GrantsAdminsFullAccess;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Quote $quote): bool
    {
        return $quote->customer_id === $user->id;
    }

    public function accept(User $user, Quote $quote): bool
    {
        return $quote->customer_id === $user->id && $quote->canBeAcceptedOrRejected();
    }

    public function reject(User $user, Quote $quote): bool
    {
        return $quote->customer_id === $user->id && $quote->canBeAcceptedOrRejected();
    }
}
