<?php

namespace App\Http\Controllers;

use App\Http\Requests\RespondToQuoteRequest;
use App\Models\Quote;
use App\Services\ActivityLogService;
use App\Services\QuoteNotificationService;
use App\Services\QuotePdfService;
use App\Services\QuoteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class UserQuoteController extends Controller
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
            ->where('customer_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return view('pages.publicUserView.quotes.index', [
            'quotes' => $quotes,
        ]);
    }

    public function show(Request $request, Quote $quote): View
    {
        $this->authorize('view', $quote);

        $quote = $this->quoteService->expireIfNeeded($quote, notify: true);

        if ($quote->status === 'sent') {
            $quote = $this->quoteService->markViewed($quote);
        }

        $quote->load(['items', 'statusHistories.changedBy', 'flightBookingRequest']);

        return view('pages.publicUserView.quotes.show', [
            'quote' => $quote,
        ]);
    }

    public function accept(RespondToQuoteRequest $request, Quote $quote): RedirectResponse
    {
        $this->authorize('accept', $quote);

        $quote = $this->quoteService->accept($quote, $request->input('comment'));
        $this->notifications->notifyAccepted($quote);
        $this->activityLog->log('quote.accepted', $quote, ['by' => 'customer']);

        return back()->with('success', 'Thank you. You have accepted this quote.');
    }

    public function reject(RespondToQuoteRequest $request, Quote $quote): RedirectResponse
    {
        $this->authorize('reject', $quote);

        $quote = $this->quoteService->reject($quote, $request->input('comment'));
        $this->notifications->notifyRejected($quote);
        $this->activityLog->log('quote.rejected', $quote, ['by' => 'customer']);

        return back()->with('success', 'Quote rejected. Our team will contact you if needed.');
    }

    public function pdf(Request $request, Quote $quote): Response
    {
        $this->authorize('view', $quote);

        return $this->pdfService->pdfResponse($quote);
    }
}
