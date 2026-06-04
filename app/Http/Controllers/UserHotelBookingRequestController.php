<?php

namespace App\Http\Controllers;

use App\Models\HotelBookingRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserHotelBookingRequestController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = HotelBookingRequest::query()
            ->where('customer_id', $request->user()->id)
            ->with(['hotel', 'room'])
            ->latest()
            ->paginate(15);

        return view('pages.publicUserView.hotel-bookings.index', [
            'bookings' => $bookings,
        ]);
    }

    public function show(Request $request, HotelBookingRequest $hotelBookingRequest): View
    {
        abort_unless($hotelBookingRequest->customer_id === $request->user()->id, 403);

        $hotelBookingRequest->load([
            'guests',
            'hotel',
            'room',
            'statusHistories' => fn ($q) => $q->latest(),
            'quotes',
        ]);

        return view('pages.publicUserView.hotel-bookings.show', [
            'booking' => $hotelBookingRequest,
        ]);
    }
}
