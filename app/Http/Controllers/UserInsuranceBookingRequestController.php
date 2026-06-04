<?php

namespace App\Http\Controllers;

use App\Models\InsuranceBookingRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserInsuranceBookingRequestController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = InsuranceBookingRequest::query()
            ->where('customer_id', $request->user()->id)
            ->with('travelInsurance')
            ->latest()
            ->paginate(15);

        return view('pages.publicUserView.insurance-bookings.index', [
            'bookings' => $bookings,
        ]);
    }

    public function show(Request $request, InsuranceBookingRequest $insuranceBookingRequest): View
    {
        abort_unless($insuranceBookingRequest->customer_id === $request->user()->id, 403);

        $insuranceBookingRequest->load([
            'travelers',
            'travelInsurance.benefits',
            'documents',
            'statusHistories' => fn ($q) => $q->latest(),
            'quotes',
        ]);

        return view('pages.publicUserView.insurance-bookings.show', [
            'booking' => $insuranceBookingRequest,
        ]);
    }
}
