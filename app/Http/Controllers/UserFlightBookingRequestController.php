<?php

namespace App\Http\Controllers;

use App\Models\FlightBookingRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserFlightBookingRequestController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = FlightBookingRequest::query()
            ->where('user_id', $request->user()->id)
            ->withCount('passengers')
            ->latest()
            ->paginate(15);

        return view('pages.publicUserView.flight-bookings.index', [
            'bookings' => $bookings,
        ]);
    }

    public function show(Request $request, FlightBookingRequest $flightBookingRequest): View
    {
        abort_unless($flightBookingRequest->user_id === $request->user()->id, 403);

        $flightBookingRequest->load(['passengers', 'statusHistories']);

        return view('pages.publicUserView.flight-bookings.show', [
            'booking' => $flightBookingRequest,
            'assistanceOptions' => FlightBookingRequest::SPECIAL_ASSISTANCE_OPTIONS,
        ]);
    }
}
