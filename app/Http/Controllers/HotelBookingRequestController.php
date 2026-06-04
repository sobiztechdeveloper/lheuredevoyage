<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesBookingConfirmation;
use App\Http\Requests\StoreHotelBookingRequest;
use App\Models\Hotel;
use App\Models\HotelBookingRequest;
use App\Models\HotelRoom;
use App\Models\HotelSearch;
use App\Services\ActivityLogService;
use App\Services\HotelBookingNotificationService;
use App\Services\HotelBookingRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HotelBookingRequestController extends Controller
{
    use AuthorizesBookingConfirmation;

    public function __construct(
        protected HotelBookingRequestService $hotelBookingService,
        protected HotelBookingNotificationService $notifications,
        protected ActivityLogService $activityLog,
    ) {}

    public function create(Request $request, Hotel $hotel): View
    {
        if (! Hotel::query()->active()->whereKey($hotel->id)->exists()) {
            abort(404);
        }

        $hotel->load(['activeRooms']);

        $room = null;
        if ($request->filled('room_id')) {
            $room = $hotel->activeRooms()->where('id', $request->integer('room_id'))->first();
        }

        if (! $room && $hotel->activeRooms->isNotEmpty()) {
            $room = $this->resolveRoomFromSearchLabel($hotel, $request->input('room-type'));
        }

        $searchParams = $this->resolveBookingSearchParams($request);
        $context = $this->hotelBookingService->buildBookingContext($hotel, $room, $searchParams);

        return view('pages.publicView.hotel.hotelBookingWizard', [
            'hotel' => $hotel,
            'room' => $room,
            'context' => $context,
            'summary' => \App\Support\BookingWizardSummary::forHotel($hotel, $room, $context),
            'bedPreferences' => HotelBookingRequest::BED_PREFERENCES,
            'smokingPreferences' => HotelBookingRequest::SMOKING_PREFERENCES,
            'arrivalTimes' => HotelBookingRequest::ARRIVAL_TIMES,
            'specialOptions' => HotelBookingRequest::SPECIAL_REQUEST_OPTIONS,
            'searchQuery' => $searchParams,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function resolveBookingSearchParams(Request $request): array
    {
        $params = $request->query();

        if ($request->filled('hotel_search')) {
            $search = HotelSearch::query()->find($request->integer('hotel_search'));
            if ($search) {
                $params = array_merge($search->bookingQueryParams(), $params);
            }
        }

        return $params;
    }

    protected function resolveRoomFromSearchLabel(Hotel $hotel, mixed $roomType): ?HotelRoom
    {
        $roomType = trim((string) $roomType);
        if ($roomType === '') {
            return $hotel->activeRooms->first();
        }

        $keywords = match (strtolower($roomType)) {
            'single room' => ['single'],
            'double room' => ['double', 'standard'],
            'deluxe room' => ['deluxe'],
            default => [strtolower($roomType), str_replace(' room', '', strtolower($roomType))],
        };

        return $hotel->activeRooms->first(function (HotelRoom $room) use ($keywords) {
            $haystack = strtolower(implode(' ', array_filter([
                $room->name,
                $room->room_type,
                $room->bed_type,
            ])));

            foreach ($keywords as $keyword) {
                if ($keyword !== '' && str_contains($haystack, $keyword)) {
                    return true;
                }
            }

            return false;
        }) ?? $hotel->activeRooms->first();
    }

    public function store(StoreHotelBookingRequest $request): RedirectResponse
    {
        $hotel = Hotel::query()->active()->findOrFail($request->input('hotel_id'));
        $room = $request->filled('room_id')
            ? HotelRoom::query()->where('hotel_id', $hotel->id)->where('id', $request->input('room_id'))->first()
            : null;

        $booking = $this->hotelBookingService->create(
            $hotel,
            $room,
            $request->validated(),
        );

        $this->notifications->notifyCreated($booking);
        $this->activityLog->log('hotel_booking_request.created', $booking, [
            'reference' => $booking->reference_number,
        ]);

        return $this->redirectToSignedBookingConfirmation('hotel.booking.confirmation', $booking);
    }

    public function confirmation(Request $request, HotelBookingRequest $hotelBookingRequest): View
    {
        $this->authorizeBookingConfirmation($request, $hotelBookingRequest);

        $hotelBookingRequest->load(['guests', 'hotel', 'room']);

        return view('pages.publicView.hotel.hotelBookingConfirmation', [
            'booking' => $hotelBookingRequest,
        ]);
    }
}
