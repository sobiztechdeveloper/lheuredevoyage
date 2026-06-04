<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

trait AuthorizesBookingConfirmation
{
    protected function redirectToSignedBookingConfirmation(string $routeName, Model $booking): RedirectResponse
    {
        return redirect()->to(URL::temporarySignedRoute(
            $routeName,
            now()->addDays(30),
            [$this->bookingConfirmationRouteKey($booking) => $booking],
        ));
    }

    protected function authorizeBookingConfirmation(Request $request, Model $booking, string $ownerColumn = 'customer_id'): void
    {
        if ($request->hasValidSignature()) {
            return;
        }

        $userId = auth()->id();

        if ($userId && (int) $booking->{$ownerColumn} === (int) $userId) {
            return;
        }

        abort(403, 'You are not authorized to view this confirmation.');
    }

    protected function bookingConfirmationRouteKey(Model $booking): string
    {
        return match ($booking::class) {
            \App\Models\FlightBookingRequest::class => 'flightBookingRequest',
            \App\Models\HotelBookingRequest::class => 'hotelBookingRequest',
            \App\Models\CruiseBookingRequest::class => 'cruiseBookingRequest',
            \App\Models\CarBookingRequest::class => 'carBookingRequest',
            \App\Models\InsuranceBookingRequest::class => 'insuranceBookingRequest',
            default => 'booking',
        };
    }

    protected function bookingConfirmationOwnerColumn(Model $booking): string
    {
        return $booking instanceof \App\Models\FlightBookingRequest ? 'user_id' : 'customer_id';
    }
}
