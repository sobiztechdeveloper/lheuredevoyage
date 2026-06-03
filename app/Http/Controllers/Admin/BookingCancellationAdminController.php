<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingCancellationRequest;
use App\Models\UserBookingHistory;
use App\Services\ActivityLogService;
use App\Services\BookingNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingCancellationAdminController extends Controller
{
    public function __construct(
        protected BookingNotificationService $bookingNotifications,
        protected ActivityLogService $activityLog,
    ) {}

    public function index(Request $request): View
    {
        $requests = BookingCancellationRequest::query()
            ->with(['booking.user', 'booking.bookable', 'user'])
            ->when($request->input('status'), fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.cancellation-requests.index', [
            'requests' => $requests,
            'filterStatus' => $request->input('status'),
        ]);
    }

    public function update(Request $request, BookingCancellationRequest $cancellationRequest): RedirectResponse
    {
        if (! $cancellationRequest->isPending()) {
            return back()->withErrors(['status' => 'This request has already been reviewed.']);
        }

        $data = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $cancellationRequest->update([
            'status' => $data['status'],
            'admin_notes' => $data['admin_notes'] ?? null,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        $booking = $cancellationRequest->booking;
        $previous = $booking->status;

        if ($data['status'] === 'approved') {
            $booking->update(['status' => 'cancelled']);
            UserBookingHistory::query()->create([
                'user_id' => $booking->user_id,
                'booking_id' => $booking->id,
                'status' => 'cancelled',
                'notes' => 'Cancellation approved: '.($data['admin_notes'] ?? 'No notes'),
            ]);

            if ($previous !== 'cancelled') {
                $this->bookingNotifications->notifyStatusChanged($booking, $previous);
            }
        }

        $this->activityLog->log('booking_cancellation.'.$data['status'], $cancellationRequest, $data);

        return redirect()
            ->route('admin.cancellation-requests.index')
            ->with('success', 'Cancellation request '.$data['status'].'.');
    }
}
