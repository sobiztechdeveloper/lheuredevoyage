<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CruiseBookingRequest;
use App\Services\ActivityLogService;
use App\Services\CruiseBookingNotificationService;
use App\Services\CruiseBookingRequestService;
use App\Services\CruiseDocumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CruiseBookingRequestAdminController extends Controller
{
    public function __construct(
        protected CruiseBookingRequestService $cruiseBookingService,
        protected CruiseBookingNotificationService $notifications,
        protected CruiseDocumentService $documentService,
        protected ActivityLogService $activityLog,
    ) {}

    public function index(Request $request): View
    {
        $requests = CruiseBookingRequest::query()
            ->with('cruise')
            ->withCount('passengers')
            ->when($request->input('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->input('q'), function ($q, $term) {
                $q->where(function ($inner) use ($term) {
                    $inner->where('reference_number', 'like', "%{$term}%")
                        ->orWhere('contact_name', 'like', "%{$term}%")
                        ->orWhere('contact_email', 'like', "%{$term}%")
                        ->orWhereHas('cruise', fn ($c) => $c->where('title', 'like', "%{$term}%")->orWhere('name', 'like', "%{$term}%"));
                });
            })
            ->when($request->input('ship'), fn ($q, $ship) => $q->whereHas('cruise', fn ($c) => $c->where('ship_name', 'like', "%{$ship}%")))
            ->when($request->input('departure_from'), fn ($q, $from) => $q->whereDate('departure_date', '>=', $from))
            ->when($request->input('departure_to'), fn ($q, $to) => $q->whereDate('departure_date', '<=', $to))
            ->when($request->input('submitted_from'), fn ($q, $from) => $q->whereDate('created_at', '>=', $from))
            ->when($request->input('submitted_to'), fn ($q, $to) => $q->whereDate('created_at', '<=', $to))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.cruise-booking-requests.index', [
            'requests' => $requests,
            'statuses' => CruiseBookingRequest::STATUSES,
            'search' => $request->input('q'),
            'filterStatus' => $request->input('status'),
            'departureFrom' => $request->input('departure_from'),
            'departureTo' => $request->input('departure_to'),
        ]);
    }

    public function show(CruiseBookingRequest $cruiseBookingRequest): View
    {
        $cruiseBookingRequest->load([
            'passengers',
            'cruise.itineraryDays',
            'cabin',
            'customer',
            'documents',
            'statusHistories' => fn ($q) => $q->latest(),
            'statusHistories.changedBy',
            'quotes',
        ]);

        return view('admin.cruise-booking-requests.show', [
            'bookingRequest' => $cruiseBookingRequest,
            'statuses' => CruiseBookingRequest::STATUSES,
        ]);
    }

    public function update(Request $request, CruiseBookingRequest $cruiseBookingRequest): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', CruiseBookingRequest::STATUSES)],
            'agent_notes' => ['nullable', 'string', 'max:5000'],
            'status_note' => ['nullable', 'string', 'max:1000'],
            'voucher' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'invoice' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'ticket' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'boarding_instructions' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'excursion_details' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $previousStatus = $cruiseBookingRequest->status;
        $newStatus = $data['status'];
        unset($data['status'], $data['status_note']);

        $adminUploads = [
            'voucher' => ['column' => 'voucher_path', 'type' => 'voucher'],
            'invoice' => ['column' => 'invoice_path', 'type' => 'invoice'],
            'ticket' => ['column' => 'ticket_path', 'type' => 'ticket'],
            'boarding_instructions' => ['column' => 'boarding_instructions_path', 'type' => 'boarding'],
            'excursion_details' => ['column' => 'excursion_details_path', 'type' => 'excursion'],
        ];
        foreach ($adminUploads as $input => $meta) {
            if ($request->hasFile($input)) {
                $data[$meta['column']] = $this->documentService->storeAdminPdf(
                    $cruiseBookingRequest,
                    $request->file($input),
                    $meta['type'],
                );
            }
        }

        unset($data['voucher'], $data['invoice'], $data['ticket'], $data['boarding_instructions'], $data['excursion_details']);
        $data['updated_by'] = auth()->id();
        $cruiseBookingRequest->update($data);

        if ($previousStatus !== $newStatus) {
            $this->cruiseBookingService->updateStatus(
                $cruiseBookingRequest,
                $newStatus,
                $request->input('status_note'),
            );
            $this->notifications->notifyStatusChanged($cruiseBookingRequest->fresh(), $previousStatus);
        }

        if ($request->hasFile('voucher')) {
            $this->notifications->notifyDocumentUploaded($cruiseBookingRequest->fresh(), 'Cruise Voucher');
        }
        if ($request->hasFile('invoice')) {
            $this->notifications->notifyDocumentUploaded($cruiseBookingRequest->fresh(), 'Invoice');
        }
        if ($request->hasFile('ticket')) {
            $this->notifications->notifyDocumentUploaded($cruiseBookingRequest->fresh(), 'Cruise Ticket');
        }

        $this->activityLog->log('cruise_booking_request.updated', $cruiseBookingRequest, [
            'reference' => $cruiseBookingRequest->reference_number,
        ]);

        return back()->with('success', 'Cruise booking request updated.');
    }
}
