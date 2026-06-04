<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelBookingRequest;
use App\Services\ActivityLogService;
use App\Services\HotelBookingNotificationService;
use App\Services\HotelBookingRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HotelBookingRequestAdminController extends Controller
{
    public function __construct(
        protected HotelBookingRequestService $hotelBookingService,
        protected HotelBookingNotificationService $notifications,
        protected ActivityLogService $activityLog,
    ) {}

    public function index(Request $request): View
    {
        $requests = HotelBookingRequest::query()
            ->with(['hotel', 'room'])
            ->withCount('guests')
            ->when($request->input('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->input('q'), function ($q, $term) {
                $q->where(function ($inner) use ($term) {
                    $inner->where('reference_number', 'like', "%{$term}%")
                        ->orWhere('lead_guest_name', 'like', "%{$term}%")
                        ->orWhere('lead_guest_email', 'like', "%{$term}%")
                        ->orWhereHas('hotel', fn ($h) => $h->where('title', 'like', "%{$term}%")->orWhere('name', 'like', "%{$term}%"));
                });
            })
            ->when($request->input('check_in_from'), fn ($q, $from) => $q->whereDate('check_in_date', '>=', $from))
            ->when($request->input('check_in_to'), fn ($q, $to) => $q->whereDate('check_in_date', '<=', $to))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.hotel-booking-requests.index', [
            'requests' => $requests,
            'statuses' => HotelBookingRequest::STATUSES,
            'search' => $request->input('q'),
            'filterStatus' => $request->input('status'),
            'checkInFrom' => $request->input('check_in_from'),
            'checkInTo' => $request->input('check_in_to'),
        ]);
    }

    public function show(HotelBookingRequest $hotelBookingRequest): View
    {
        $hotelBookingRequest->load([
            'guests',
            'hotel',
            'room',
            'customer',
            'statusHistories' => fn ($q) => $q->latest(),
            'statusHistories.changedBy',
            'quotes',
        ]);

        return view('admin.hotel-booking-requests.show', [
            'bookingRequest' => $hotelBookingRequest,
            'statuses' => HotelBookingRequest::STATUSES,
            'specialOptions' => HotelBookingRequest::SPECIAL_REQUEST_OPTIONS,
        ]);
    }

    public function update(Request $request, HotelBookingRequest $hotelBookingRequest): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', HotelBookingRequest::STATUSES)],
            'agent_notes' => ['nullable', 'string', 'max:5000'],
            'status_note' => ['nullable', 'string', 'max:1000'],
            'voucher' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'invoice' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'transfer_voucher' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $previousStatus = $hotelBookingRequest->status;
        $newStatus = $data['status'];
        unset($data['status'], $data['status_note']);

        foreach (['voucher' => 'voucher_path', 'invoice' => 'invoice_path', 'transfer_voucher' => 'transfer_voucher_path'] as $input => $column) {
            if ($request->hasFile($input)) {
                if ($hotelBookingRequest->{$column}) {
                    Storage::disk('public')->delete($hotelBookingRequest->{$column});
                }
                $data[$column] = $request->file($input)->store('hotel-bookings/'.str_replace('_path', '', $column).'s', 'public');
            }
        }

        unset($data['voucher'], $data['invoice'], $data['transfer_voucher']);
        $data['updated_by'] = auth()->id();

        $hotelBookingRequest->update($data);

        if ($previousStatus !== $newStatus) {
            $this->hotelBookingService->updateStatus(
                $hotelBookingRequest,
                $newStatus,
                $request->input('status_note'),
            );
            $this->notifications->notifyStatusChanged($hotelBookingRequest->fresh(), $previousStatus);
        }

        if ($request->hasFile('voucher')) {
            $this->notifications->notifyDocumentUploaded($hotelBookingRequest->fresh(), 'Hotel Voucher');
        }
        if ($request->hasFile('invoice')) {
            $this->notifications->notifyDocumentUploaded($hotelBookingRequest->fresh(), 'Invoice');
        }
        if ($request->hasFile('transfer_voucher')) {
            $this->notifications->notifyDocumentUploaded($hotelBookingRequest->fresh(), 'Transfer Voucher');
        }

        $this->activityLog->log('hotel_booking_request.updated', $hotelBookingRequest, [
            'reference' => $hotelBookingRequest->reference_number,
        ]);

        return back()->with('success', 'Hotel booking request updated.');
    }
}
