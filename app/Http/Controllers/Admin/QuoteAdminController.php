<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreQuoteRequest;
use App\Http\Requests\Admin\UpdateQuoteRequest;
use App\Models\FlightBookingRequest;
use App\Models\Quote;
use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\QuoteNotificationService;
use App\Services\QuotePdfService;
use App\Services\QuoteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class QuoteAdminController extends Controller
{
    public function __construct(
        protected QuoteService $quoteService,
        protected QuoteNotificationService $notifications,
        protected QuotePdfService $pdfService,
        protected ActivityLogService $activityLog,
    ) {}

    public function index(Request $request): View
    {
        $quotes = Quote::query()
            ->with('customer')
            ->when($request->input('q'), function ($q, $term) {
                $q->where(function ($inner) use ($term) {
                    $inner->where('quote_number', 'like', "%{$term}%")
                        ->orWhere('title', 'like', "%{$term}%")
                        ->orWhereHas('customer', fn ($c) => $c->where('name', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%"));
                });
            })
            ->when($request->input('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->input('quote_type'), fn ($q, $type) => $q->where('quote_type', $type))
            ->when($request->input('from'), fn ($q, $from) => $q->whereDate('created_at', '>=', $from))
            ->when($request->input('to'), fn ($q, $to) => $q->whereDate('created_at', '<=', $to))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.quotes.index', [
            'quotes' => $quotes,
            'statuses' => Quote::STATUSES,
            'types' => Quote::TYPES,
            'search' => $request->input('q'),
            'filterStatus' => $request->input('status'),
            'filterType' => $request->input('quote_type'),
            'from' => $request->input('from'),
            'to' => $request->input('to'),
        ]);
    }

    public function create(Request $request): View
    {
        $flightRequest = $request->filled('flight_booking_request_id')
            ? FlightBookingRequest::query()->with('user')->find($request->input('flight_booking_request_id'))
            : null;

        return view('admin.quotes.form', [
            'quote' => new Quote([
                'currency' => $flightRequest?->currency ?? 'USD',
                'status' => 'draft',
                'valid_until' => now()->addDays(7),
                'customer_id' => $flightRequest?->user_id,
                'flight_booking_request_id' => $flightRequest?->id,
            ]),
            'customers' => $this->customerOptions(),
            'flightRequest' => $flightRequest,
            'types' => Quote::TYPES,
            'statuses' => Quote::STATUSES,
            'isEdit' => false,
        ]);
    }

    public function store(StoreQuoteRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $items = $data['items'];
        unset($data['items']);

        $quote = $this->quoteService->create($data, $items);

        $this->activityLog->log('quote.created', $quote, ['quote_number' => $quote->quote_number]);

        if ($request->input('action') === 'send') {
            return $this->sendQuote($quote);
        }

        return redirect()
            ->route('admin.quotes.show', $quote)
            ->with('success', 'Quote created successfully.');
    }

    public function show(Quote $quote): View
    {
        $quote->load(['items', 'customer', 'flightBookingRequest', 'statusHistories.changedBy', 'creator', 'updater']);
        $this->quoteService->expireIfNeeded($quote);
        $quote->refresh();

        return view('admin.quotes.show', [
            'quote' => $quote,
            'statuses' => Quote::STATUSES,
        ]);
    }

    public function edit(Quote $quote): View
    {
        $quote->load(['items', 'customer', 'flightBookingRequest']);

        return view('admin.quotes.form', [
            'quote' => $quote,
            'customers' => $this->customerOptions(),
            'flightRequest' => $quote->flightBookingRequest,
            'types' => Quote::TYPES,
            'statuses' => Quote::STATUSES,
            'isEdit' => true,
        ]);
    }

    public function update(UpdateQuoteRequest $request, Quote $quote): RedirectResponse
    {
        $data = $request->validated();
        $items = $data['items'];
        unset($data['items']);

        $previousStatus = $quote->status;
        $quote = $this->quoteService->update($quote, $data, $items);

        $this->activityLog->log('quote.updated', $quote, [
            'quote_number' => $quote->quote_number,
            'status' => $quote->status,
        ]);

        if ($request->input('action') === 'send' && $quote->status === 'draft') {
            return $this->sendQuote($quote);
        }

        if ($previousStatus !== $quote->status && $quote->status === 'expired') {
            $this->notifications->notifyExpired($quote);
        }

        return redirect()
            ->route('admin.quotes.show', $quote)
            ->with('success', 'Quote updated successfully.');
    }

    public function destroy(Quote $quote): RedirectResponse
    {
        $this->activityLog->log('quote.deleted', $quote, ['quote_number' => $quote->quote_number]);
        $quote->delete();

        return redirect()
            ->route('admin.quotes.index')
            ->with('success', 'Quote deleted.');
    }

    public function createFromFlight(FlightBookingRequest $flightBookingRequest): RedirectResponse
    {
        $quote = $this->quoteService->createDraftFromFlightRequest($flightBookingRequest);

        $this->activityLog->log('quote.created_from_flight', $quote, [
            'flight_reference' => $flightBookingRequest->booking_reference,
        ]);

        return redirect()
            ->route('admin.quotes.edit', $quote)
            ->with('success', 'Draft quote generated from flight request. Review and send when ready.');
    }

    public function send(Quote $quote): RedirectResponse
    {
        return $this->sendQuote($quote);
    }

    public function pdf(Quote $quote): Response
    {
        return $this->pdfService->pdfResponse($quote);
    }

    protected function sendQuote(Quote $quote): RedirectResponse
    {
        if (! $quote->customer_id && ! $quote->flightBookingRequest?->email) {
            return back()->withErrors(['customer_id' => 'Assign a customer or link a flight request with contact email before sending.']);
        }

        $quote = $this->quoteService->send($quote);
        $this->notifications->notifySent($quote);
        $this->activityLog->log('quote.sent', $quote, ['quote_number' => $quote->quote_number]);

        return redirect()
            ->route('admin.quotes.show', $quote)
            ->with('success', 'Quote sent to customer.');
    }

    /**
     * @return \Illuminate\Support\Collection<int, User>
     */
    protected function customerOptions()
    {
        return User::query()
            ->where('is_admin', false)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
    }
}
