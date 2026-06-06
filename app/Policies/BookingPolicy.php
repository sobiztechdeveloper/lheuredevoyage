<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use App\Policies\Concerns\GrantsAdminsFullAccess;

class BookingPolicy
{
    use GrantsAdminsFullAccess;

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id;
    }

    public function update(User $user, Booking $booking): bool
    {
        return false;
    }

    public function cancel(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id
            && ! in_array($booking->status, ['cancelled', 'completed'], true);
    }
}
