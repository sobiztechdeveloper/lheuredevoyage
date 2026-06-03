<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlightBookingRequest;
use App\Services\ActivityLogService;
use App\Services\FlightBookingNotificationService;
use App\Services\FlightBookingRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class FlightBookingRequestAdminController extends Controller
{
    public function __construct(
        protected FlightBookingRequestService $flightBookingService,
        protected FlightBookingNotificationService $notifications,
        protected ActivityLogService $activityLog,
    ) {}

    public function index(Request $request): View
    {
        $from = $request->input('from');
        $to = $request->input('to');

        $requests = FlightBookingRequest::query()
            ->withCount('passengers')
            ->when($request->input('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->input('q'), function ($q, $term) {
                $q->where(function ($inner) use ($term) {
                    $inner->where('booking_reference', 'like', "%{$term}%")
                        ->orWhere('contact_name', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%")
                        ->orWhere('phone', 'like', "%{$term}%")
                        ->orWhere('origin', 'like', "%{$term}%")
                        ->orWhere('destination', 'like', "%{$term}%");
                });
            })
            ->when($from, fn ($q) => $q->whereDate('departure_date', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('departure_date', '<=', $to))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.flight-booking-requests.index', [
            'requests' => $requests,
            'statuses' => FlightBookingRequest::STATUSES,
            'search' => $request->input('q'),
            'filterStatus' => $request->input('status'),
            'from' => $from,
            'to' => $to,
        ]);
    }

    public function show(FlightBookingRequest $flightBookingRequest): View
    {
        $flightBookingRequest->load([
            'passengers',
            'statusHistories' => fn ($q) => $q->latest(),
            'statusHistories.changedBy',
            'user',
            'flightSearchResult',
            'quotes',
        ]);

        return view('admin.flight-booking-requests.show', [
            'bookingRequest' => $flightBookingRequest,
            'statuses' => FlightBookingRequest::STATUSES,
            'assistanceOptions' => FlightBookingRequest::SPECIAL_ASSISTANCE_OPTIONS,
        ]);
    }

    public function update(Request $request, FlightBookingRequest $flightBookingRequest): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', FlightBookingRequest::STATUSES)],
            'agent_notes' => ['nullable', 'string', 'max:5000'],
            'status_note' => ['nullable', 'string', 'max:1000'],
            'ticket' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'invoice' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $previousStatus = $flightBookingRequest->status;
        $newStatus = $data['status'];
        unset($data['status'], $data['status_note']);

        $ticketUploaded = false;
        $invoiceUploaded = false;

        if ($request->hasFile('ticket')) {
            if ($flightBookingRequest->ticket_path) {
                Storage::disk('public')->delete($flightBookingRequest->ticket_path);
            }
            $data['ticket_path'] = $request->file('ticket')->store('flight-bookings/tickets', 'public');
            $ticketUploaded = true;
        }

        if ($request->hasFile('invoice')) {
            if ($flightBookingRequest->invoice_path) {
                Storage::disk('public')->delete($flightBookingRequest->invoice_path);
            }
            $data['invoice_path'] = $request->file('invoice')->store('flight-bookings/invoices', 'public');
            $invoiceUploaded = true;
        }

        unset($data['ticket'], $data['invoice']);

        $flightBookingRequest->update($data);

        if ($previousStatus !== $newStatus) {
            $this->flightBookingService->updateStatus(
                $flightBookingRequest,
                $newStatus,
                $request->input('status_note'),
            );
            $this->notifications->notifyStatusChanged($flightBookingRequest->fresh(), $previousStatus);
        }

        if ($ticketUploaded) {
            $this->notifications->notifyDocumentUploaded($flightBookingRequest->fresh(), 'ticket');
        }

        if ($invoiceUploaded) {
            $this->notifications->notifyDocumentUploaded($flightBookingRequest->fresh(), 'invoice');
        }

        $this->activityLog->log('flight_booking_request.updated', $flightBookingRequest, [
            'status' => $newStatus,
            'previous_status' => $previousStatus,
        ]);

        return back()->with('success', 'Flight booking request updated.');
    }
}
