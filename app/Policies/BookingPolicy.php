<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

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
