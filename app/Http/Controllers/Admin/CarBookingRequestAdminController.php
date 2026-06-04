<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarBookingRequest;
use App\Services\ActivityLogService;
use App\Services\CarBookingNotificationService;
use App\Services\CarBookingRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CarBookingRequestAdminController extends Controller
{
    public function __construct(
        protected CarBookingRequestService $carBookingService,
        protected CarBookingNotificationService $notifications,
        protected ActivityLogService $activityLog,
    ) {}

    public function index(Request $request): View
    {
        $requests = CarBookingRequest::query()
            ->with('rentalCar')
            ->withCount('drivers')
            ->when($request->input('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->input('q'), function ($q, $term) {
                $q->where(function ($inner) use ($term) {
                    $inner->where('reference_number', 'like', "%{$term}%")
                        ->orWhere('contact_email', 'like', "%{$term}%")
                        ->orWhere('contact_phone', 'like', "%{$term}%")
                        ->orWhere('pickup_location', 'like', "%{$term}%")
                        ->orWhere('dropoff_location', 'like', "%{$term}%")
                        ->orWhereHas('rentalCar', fn ($c) => $c->where('title', 'like', "%{$term}%")->orWhere('name', 'like', "%{$term}%"));
                });
            })
            ->when($request->input('pickup_from'), fn ($q, $from) => $q->whereDate('pickup_date', '>=', $from))
            ->when($request->input('pickup_to'), fn ($q, $to) => $q->whereDate('pickup_date', '<=', $to))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.car-booking-requests.index', [
            'requests' => $requests,
            'statuses' => CarBookingRequest::STATUSES,
            'search' => $request->input('q'),
            'filterStatus' => $request->input('status'),
            'pickupFrom' => $request->input('pickup_from'),
            'pickupTo' => $request->input('pickup_to'),
        ]);
    }

    public function show(CarBookingRequest $carBookingRequest): View
    {
        $carBookingRequest->load([
            'drivers',
            'rentalCar',
            'customer',
            'statusHistories' => fn ($q) => $q->latest(),
            'statusHistories.changedBy',
            'quotes',
        ]);

        return view('admin.car-booking-requests.show', [
            'bookingRequest' => $carBookingRequest,
            'statuses' => CarBookingRequest::STATUSES,
        ]);
    }

    public function update(Request $request, CarBookingRequest $carBookingRequest): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', CarBookingRequest::STATUSES)],
            'agent_notes' => ['nullable', 'string', 'max:5000'],
            'status_note' => ['nullable', 'string', 'max:1000'],
            'voucher' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'invoice' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'rental_agreement' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $previousStatus = $carBookingRequest->status;
        $newStatus = $data['status'];
        unset($data['status'], $data['status_note']);

        foreach (['voucher' => 'voucher_path', 'invoice' => 'invoice_path', 'rental_agreement' => 'rental_agreement_path'] as $input => $column) {
            if ($request->hasFile($input)) {
                if ($carBookingRequest->{$column}) {
                    Storage::disk('public')->delete($carBookingRequest->{$column});
                }
                $data[$column] = $request->file($input)->store('car-bookings/'.str_replace('_path', '', $column).'s', 'public');
            }
        }

        unset($data['voucher'], $data['invoice'], $data['rental_agreement']);
        $data['updated_by'] = auth()->id();
        $carBookingRequest->update($data);

        if ($previousStatus !== $newStatus) {
            $this->carBookingService->updateStatus(
                $carBookingRequest,
                $newStatus,
                $request->input('status_note'),
            );
            $this->notifications->notifyStatusChanged($carBookingRequest->fresh(), $previousStatus);
        }

        if ($request->hasFile('voucher')) {
            $this->notifications->notifyDocumentUploaded($carBookingRequest->fresh(), 'Car Voucher');
        }
        if ($request->hasFile('invoice')) {
            $this->notifications->notifyDocumentUploaded($carBookingRequest->fresh(), 'Invoice');
        }
        if ($request->hasFile('rental_agreement')) {
            $this->notifications->notifyDocumentUploaded($carBookingRequest->fresh(), 'Rental Agreement');
        }

        $this->activityLog->log('car_booking_request.updated', $carBookingRequest, [
            'reference' => $carBookingRequest->reference_number,
        ]);

        return back()->with('success', 'Car booking request updated.');
    }
}
