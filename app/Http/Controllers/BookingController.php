<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Cruise;
use App\Models\Hotel;
use App\Models\RentalCar;
use App\Models\TourPackage;
use App\Models\TravelInsurance;
use App\Models\UserBookingHistory;
use App\Models\UserMyBooking;
use App\Services\BookingNotificationService;
use Illuminate\Http\RedirectResponse;

class BookingController extends Controller
{
    private const TYPE_MAP = [
        'hotel' => Hotel::class,
        'cruise' => Cruise::class,
        'rentalcar' => RentalCar::class,
        'travelinsurance' => TravelInsurance::class,
        'tourpackage' => TourPackage::class,
    ];

    public function __construct(
        protected BookingNotificationService $bookingNotifications,
    ) {}

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $type = $request->input('bookable_type');
        $modelClass = self::TYPE_MAP[$type] ?? null;

        if (! $modelClass) {
            return back()->withErrors(['bookable_type' => 'Invalid booking type.']);
        }

        $bookable = $modelClass::query()
            ->active()
            ->where('slug', $request->input('bookable_slug'))
            ->firstOrFail();

        $amount = $bookable->price_per_day ?? $bookable->price;

        $booking = Booking::query()->create([
            'user_id' => $request->user()->id,
            'bookable_type' => $modelClass,
            'bookable_id' => $bookable->id,
            'reference' => Booking::generateReference(),
            'status' => 'pending',
            'total_amount' => $amount,
            'currency' => 'USD',
            'booking_data' => $request->only(['guest_name', 'guest_email', 'guest_phone', 'travel_date', 'notes']),
            'booked_at' => now(),
        ]);

        UserMyBooking::query()->create([
            'user_id' => $request->user()->id,
            'booking_id' => $booking->id,
        ]);

        UserBookingHistory::query()->create([
            'user_id' => $request->user()->id,
            'booking_id' => $booking->id,
            'status' => 'pending',
            'notes' => 'Booking created',
        ]);

        $this->bookingNotifications->notifyBookingCreated($booking);

        return redirect()
            ->route('my-bookings-list')
            ->with('success', 'Your booking has been submitted. Reference: '.$booking->reference);
    }
}
