<?php

namespace App\Http\Controllers;

use App\Models\CruiseBookingRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserCruiseBookingRequestController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = CruiseBookingRequest::query()
            ->where('customer_id', $request->user()->id)
            ->with('cruise')
            ->latest()
            ->paginate(15);

        return view('pages.publicUserView.cruise-bookings.index', [
            'bookings' => $bookings,
        ]);
    }

    public function show(Request $request, CruiseBookingRequest $cruiseBookingRequest): View
    {
        abort_unless($cruiseBookingRequest->customer_id === $request->user()->id, 403);

        $cruiseBookingRequest->load([
            'passengers',
            'cruise',
            'statusHistories' => fn ($q) => $q->latest(),
            'quotes',
        ]);

        return view('pages.publicUserView.cruise-bookings.show', [
            'booking' => $cruiseBookingRequest,
        ]);
    }
}
