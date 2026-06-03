<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingCancellationRequest;
use App\Models\Booking;
use App\Models\BookingCancellationRequest;
use App\Models\UserBookingHistory;
use App\Services\BookingInvoiceService;
use App\Services\BookingNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class UserBookingDetailController extends Controller
{
    public function __construct(
        protected BookingInvoiceService $invoices,
        protected BookingNotificationService $bookingNotifications,
    ) {}

    public function show(Booking $booking): View
    {
        $this->authorize('view', $booking);
        $booking->load(['bookable', 'histories', 'cancellationRequests']);

        return view('pages.publicUserView.booking-show', compact('booking'));
    }

    public function invoice(Booking $booking): View
    {
        $this->authorize('view', $booking);

        return view('pages.publicUserView.booking-invoice', $this->invoices->invoiceData($booking));
    }

    public function invoicePdf(Booking $booking): Response
    {
        $this->authorize('view', $booking);

        return $this->invoices->pdfResponse($booking);
    }

    public function requestCancellation(StoreBookingCancellationRequest $request, Booking $booking): RedirectResponse
    {
        $this->authorize('cancel', $booking);

        if ($booking->pendingCancellationRequest()->exists()) {
            return back()->withErrors(['reason' => 'A cancellation request is already pending for this booking.']);
        }

        BookingCancellationRequest::query()->create([
            'booking_id' => $booking->id,
            'user_id' => $request->user()->id,
            'reason' => $request->input('reason'),
            'status' => 'pending',
        ]);

        UserBookingHistory::query()->create([
            'user_id' => $booking->user_id,
            'booking_id' => $booking->id,
            'status' => $booking->status,
            'notes' => 'Cancellation requested by customer',
        ]);

        return back()->with('success', 'Your cancellation request has been submitted.');
    }
}
