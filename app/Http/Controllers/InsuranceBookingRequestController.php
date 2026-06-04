<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesBookingConfirmation;
use App\Http\Requests\StoreInsuranceQuoteRequest;
use App\Models\InsuranceBookingRequest;
use App\Models\TravelInsurance;
use App\Services\ActivityLogService;
use App\Services\InsuranceBookingNotificationService;
use App\Services\InsuranceBookingRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InsuranceBookingRequestController extends Controller
{
    use AuthorizesBookingConfirmation;

    public function __construct(
        protected InsuranceBookingRequestService $insuranceBookingService,
        protected InsuranceBookingNotificationService $notifications,
        protected ActivityLogService $activityLog,
    ) {}

    /** Entry: insurance quote wizard (optional pre-selected plan). */
    public function quoteWizard(Request $request, ?TravelInsurance $travelInsurance = null): View|RedirectResponse
    {
        if (! $travelInsurance) {
            $legacySlug = $this->legacyQuoteSlugFromQuery($request);
            if ($legacySlug) {
                $query = collect($request->query())
                    ->except($legacySlug)
                    ->filter(fn ($value) => $value !== null && $value !== '')
                    ->all();
                $url = route('travelinsurance.quote.wizard', ['travelInsurance' => $legacySlug]);
                if ($query !== []) {
                    $url .= '?'.http_build_query($query);
                }

                return redirect()->to($url);
            }
        }

        if ($travelInsurance && ! TravelInsurance::query()->active()->whereKey($travelInsurance->id)->exists()) {
            abort(404);
        }

        $context = $this->insuranceBookingService->buildQuoteContext($travelInsurance);
        $context['searchDefaults'] = array_filter([
            'destination_country' => $request->input('destination', $request->input('q')),
            'travel_start' => $request->input('journey-date'),
            'travel_end' => $request->input('return-date'),
            'travelers_count' => $request->input('travelers'),
        ]);

        $context['summary'] = \App\Support\BookingWizardSummary::forInsurance(
            $travelInsurance,
            $context['searchDefaults'] ?? [],
        );

        return view('pages.publicView.travelInsurance.insuranceQuoteWizard', $context);
    }

    /**
     * Old links used /request-quote?{slug}?journey-date=… — slug was a bare query key.
     */
    protected function legacyQuoteSlugFromQuery(Request $request): ?string
    {
        $reserved = ['destination', 'q', 'journey-date', 'return-date', 'travelers', 'sort'];

        foreach (array_keys($request->query()) as $key) {
            if (in_array($key, $reserved, true)) {
                continue;
            }
            if (TravelInsurance::query()->active()->where('slug', $key)->exists()) {
                return $key;
            }
        }

        return null;
    }

    /** Legacy route — redirect to quote wizard. */
    public function create(Request $request, TravelInsurance $travelInsurance): RedirectResponse
    {
        $url = route('travelinsurance.quote.wizard', ['travelInsurance' => $travelInsurance->slug]);
        if ($request->query()->isNotEmpty()) {
            $url .= '?'.$request->getQueryString();
        }

        return redirect()->to($url);
    }

    public function store(StoreInsuranceQuoteRequest $request): RedirectResponse
    {
        $booking = $this->insuranceBookingService->createQuoteRequest($request->validated());

        $this->notifications->notifyCreated($booking);
        $this->activityLog->log('insurance_booking_request.created', $booking, [
            'reference' => $booking->reference_number,
        ]);

        return $this->redirectToSignedBookingConfirmation('travelinsurance.booking.confirmation', $booking);
    }

    public function confirmation(Request $request, InsuranceBookingRequest $insuranceBookingRequest): View
    {
        $this->authorizeBookingConfirmation($request, $insuranceBookingRequest);

        $insuranceBookingRequest->load(['travelers', 'travelInsurance.benefits']);

        return view('pages.publicView.travelInsurance.insuranceBookingConfirmation', [
            'booking' => $insuranceBookingRequest,
        ]);
    }
}
