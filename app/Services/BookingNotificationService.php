<?php

namespace App\Services;

use App\Mail\BookingConfirmationMail;
use App\Models\Booking;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Mail;

class BookingNotificationService
{
    public function notifyBookingCreated(Booking $booking): void
    {
        $booking->load(['user', 'bookable']);

        UserNotification::query()->create([
            'user_id' => $booking->user_id,
            'title' => 'Booking Submitted',
            'body' => "Your booking {$booking->reference} for {$booking->bookable?->title} is pending confirmation.",
        ]);

        if ($booking->user?->email) {
            Mail::to($booking->user->email)->send(new BookingConfirmationMail($booking));
        }
    }

    public function notifyStatusChanged(Booking $booking, string $previousStatus): void
    {
        $booking->load(['user', 'bookable']);

        UserNotification::query()->create([
            'user_id' => $booking->user_id,
            'title' => 'Booking Status Updated',
            'body' => "Booking {$booking->reference} changed from {$previousStatus} to {$booking->status}.",
        ]);

        if ($booking->user?->email) {
            Mail::to($booking->user->email)->send(new BookingConfirmationMail($booking, true));
        }
    }
}
