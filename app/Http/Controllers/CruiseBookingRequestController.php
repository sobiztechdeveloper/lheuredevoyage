<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesBookingConfirmation;
use App\Http\Requests\StoreCruiseQuoteRequest;
use App\Models\Cruise;
use App\Models\CruiseBookingRequest;
use App\Services\ActivityLogService;
use App\Services\CruiseBookingNotificationService;
use App\Services\CruiseBookingRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CruiseBookingRequestController extends Controller
{
    use AuthorizesBookingConfirmation;

    public function __construct(
        protected CruiseBookingRequestService $cruiseBookingService,
        protected CruiseBookingNotificationService $notifications,
        protected ActivityLogService $activityLog,
    ) {}

    public function quoteWizard(Request $request, ?Cruise $cruise = null): View
    {
        if ($cruise && ! Cruise::query()->active()->whereKey($cruise->id)->exists()) {
            abort(404);
        }

        if ($cruise) {
            $cruise->load(['itineraryDays', 'cabins', 'galleryImages']);
        }

        $wizard = $this->cruiseBookingService->buildQuoteWizardContext($cruise, $request->query());
        if ($cruise) {
            $wizard['context'] = $this->cruiseBookingService->buildContext($cruise, $request->query());
            $wizard['summary'] = \App\Support\BookingWizardSummary::forCruise($cruise, $wizard['context']);
        } else {
            $wizard['summary'] = \App\Support\BookingWizardSummary::forCruise(null, []);
        }

        return view('pages.publicView.cruise.cruiseQuoteWizard', $wizard);
    }

    public function create(Request $request, Cruise $cruise): RedirectResponse
    {
        $url = route('cruise.quote.wizard', ['cruise' => $cruise->slug]);
        if ($request->query()->isNotEmpty()) {
            $url .= '?'.$request->getQueryString();
        }

        return redirect()->to($url);
    }

    public function store(StoreCruiseQuoteRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['documents'] = array_filter([
            'passport' => $request->file('documents.passport'),
            'visa' => $request->file('documents.visa'),
            'insurance' => $request->file('documents.insurance'),
            'other' => $request->file('documents.other'),
        ]);

        $booking = $this->cruiseBookingService->createQuoteRequest($data);

        $this->notifications->notifyCreated($booking);
        $this->activityLog->log('cruise_booking_request.created', $booking, [
            'reference' => $booking->reference_number,
        ]);

        return $this->redirectToSignedBookingConfirmation('cruise.booking.confirmation', $booking);
    }

    public function confirmation(Request $request, CruiseBookingRequest $cruiseBookingRequest): View
    {
        $this->authorizeBookingConfirmation($request, $cruiseBookingRequest);
        $cruiseBookingRequest->load(['passengers', 'cruise', 'cabin']);

        return view('pages.publicView.cruise.cruiseBookingConfirmation', [
            'booking' => $cruiseBookingRequest,
        ]);
    }
}
