<?php

namespace App\Http\Controllers;

use App\Models\CarBookingRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserCarBookingRequestController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = CarBookingRequest::query()
            ->where('customer_id', $request->user()->id)
            ->with('rentalCar')
            ->latest()
            ->paginate(15);

        return view('pages.publicUserView.car-bookings.index', [
            'bookings' => $bookings,
        ]);
    }

    public function show(Request $request, CarBookingRequest $carBookingRequest): View
    {
        abort_unless($carBookingRequest->customer_id === $request->user()->id, 403);

        $carBookingRequest->load([
            'drivers',
            'rentalCar',
            'statusHistories' => fn ($q) => $q->latest(),
            'quotes',
        ]);

        return view('pages.publicUserView.car-bookings.show', [
            'booking' => $carBookingRequest,
        ]);
    }
}
