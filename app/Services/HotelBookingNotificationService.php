<?php

namespace App\Services;

use App\Mail\HotelBookingRequestMail;
use App\Models\HotelBookingRequest;
use App\Models\UserNotification;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Mail;

class HotelBookingNotificationService
{
    public function notifyCreated(HotelBookingRequest $request): void
    {
        $request->load(['guests', 'hotel', 'room', 'customer']);

        if ($request->customer_id) {
            UserNotification::query()->create([
                'user_id' => $request->customer_id,
                'title' => 'Hotel Request Submitted',
                'body' => "Your hotel request {$request->reference_number} has been received.",
            ]);
        }

        if ($request->lead_guest_email) {
            Mail::to($request->lead_guest_email)->send(new HotelBookingRequestMail($request, 'received'));
        }

        $adminEmail = WebsiteSetting::cached()->company_email;
        if ($adminEmail) {
            Mail::to($adminEmail)->send(new HotelBookingRequestMail($request, 'admin_new'));
        }
    }

    public function notifyStatusChanged(HotelBookingRequest $request, string $previousStatus): void
    {
        $request->load(['guests', 'customer']);

        if ($request->customer_id) {
            UserNotification::query()->create([
                'user_id' => $request->customer_id,
                'title' => 'Hotel Request Updated',
                'body' => "Request {$request->reference_number} is now {$request->statusLabel()}.",
            ]);
        }

        if ($request->lead_guest_email) {
            Mail::to($request->lead_guest_email)->send(
                new HotelBookingRequestMail($request, 'status_changed', $previousStatus),
            );
        }
    }

    public function notifyDocumentUploaded(HotelBookingRequest $request, string $documentType): void
    {
        $request->load(['customer']);

        if ($request->customer_id) {
            UserNotification::query()->create([
                'user_id' => $request->customer_id,
                'title' => 'Hotel Document Available',
                'body' => "{$documentType} is available for {$request->reference_number}.",
            ]);
        }

        if ($request->lead_guest_email) {
            Mail::to($request->lead_guest_email)->send(
                new HotelBookingRequestMail($request, 'document_uploaded', documentType: $documentType),
            );
        }
    }
}
