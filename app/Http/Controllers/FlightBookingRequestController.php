<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFlightBookingRequest;
use App\Models\FlightBookingRequest;
use App\Models\FlightSearchResult;
use App\Services\ActivityLogService;
use App\Services\FlightBookingNotificationService;
use App\Services\FlightBookingRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FlightBookingRequestController extends Controller
{
    public function __construct(
        protected FlightBookingRequestService $flightBookingRequestService,
        protected FlightBookingNotificationService $notifications,
        protected ActivityLogService $activityLog,
    ) {}

    public function create(FlightSearchResult $flightSearchResult): View
    {
        $flightSearchResult->load('flightSearch');
        $search = $flightSearchResult->flightSearch;

        $passengerSlots = $this->flightBookingRequestService->passengerSlots(
            $search?->adult ?? 1,
            $search?->children ?? 0,
            $search?->infant ?? 0,
        );

        return view('pages.publicView.flight.flightBookingWizard', [
            'result' => $flightSearchResult,
            'search' => $search,
            'summary' => $this->flightBookingRequestService->buildFlightSummary($flightSearchResult),
            'passengerSlots' => $passengerSlots,
            'assistanceOptions' => FlightBookingRequest::SPECIAL_ASSISTANCE_OPTIONS,
            'seatPreferences' => FlightBookingRequest::SEAT_PREFERENCES,
            'mealPreferences' => FlightBookingRequest::MEAL_PREFERENCES,
        ]);
    }

    public function store(StoreFlightBookingRequest $request, FlightSearchResult $flightSearchResult): RedirectResponse
    {
        $flightSearchResult->load('flightSearch');

        $booking = $this->flightBookingRequestService->createFromResult(
            $flightSearchResult,
            $request->validated(),
            $request,
        );

        $this->notifications->notifyCreated($booking);
        $this->activityLog->log('flight_booking_request.created', $booking, [
            'reference' => $booking->booking_reference,
        ]);

        return redirect()->route('flight.booking.confirmation', $booking);
    }

    public function confirmation(FlightBookingRequest $flightBookingRequest): View
    {
        $flightBookingRequest->load(['passengers', 'flightSearchResult', 'flightSearch']);

        return view('pages.publicView.flight.flightBookingConfirmation', [
            'booking' => $flightBookingRequest,
        ]);
    }
}
