<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\UserBookingHistory;
use App\Services\ActivityLogService;
use App\Services\BookingInvoiceService;
use App\Services\BookingNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class BookingAdminController extends Controller
{
    public function __construct(
        protected BookingNotificationService $bookingNotifications,
        protected ActivityLogService $activityLog,
        protected BookingInvoiceService $invoices,
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Booking::class);

        $bookings = Booking::query()
            ->with(['user', 'bookable'])
            ->when($request->input('q'), function ($query, $term) {
                $query->where(function ($q) use ($term) {
                    $q->where('reference', 'like', "%{$term}%")
                        ->orWhere('status', 'like', "%{$term}%")
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%"));
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'search' => $request->input('q'),
        ]);
    }

    public function show(Booking $booking): View
    {
        $this->authorize('view', $booking);
        $booking->load(['user', 'bookable', 'histories', 'cancellationRequests']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function invoice(Booking $booking): View
    {
        $this->authorize('view', $booking);

        return view('admin.bookings.invoice', $this->invoices->invoiceData($booking));
    }

    public function invoicePdf(Booking $booking): Response
    {
        $this->authorize('view', $booking);

        return $this->invoices->pdfResponse($booking);
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $this->authorize('update', $booking);

        $data = $request->validate([
            'status' => ['required', 'in:pending,confirmed,cancelled,completed'],
        ]);

        $previous = $booking->status;
        $booking->update($data);

        UserBookingHistory::query()->create([
            'user_id' => $booking->user_id,
            'booking_id' => $booking->id,
            'status' => $booking->status,
            'notes' => 'Status updated by admin',
        ]);

        if ($previous !== $booking->status) {
            $this->bookingNotifications->notifyStatusChanged($booking, $previous);
        }

        $this->activityLog->log('booking.status_updated', $booking, [
            'from' => $previous,
            'to' => $booking->status,
        ]);

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Booking updated.');
    }
}
