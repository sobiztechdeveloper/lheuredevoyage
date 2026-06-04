<?php

namespace App\Services;

use App\Mail\CruiseBookingRequestMail;
use App\Models\CruiseBookingRequest;
use App\Models\UserNotification;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Mail;

class CruiseBookingNotificationService
{
    public function notifyCreated(CruiseBookingRequest $request): void
    {
        $request->load(['passengers', 'cruise', 'customer']);

        if ($request->customer_id) {
            UserNotification::query()->create([
                'user_id' => $request->customer_id,
                'title' => 'Cruise Request Submitted',
                'body' => "Your cruise request {$request->reference_number} has been received.",
            ]);
        }

        if ($request->contact_email) {
            Mail::to($request->contact_email)->send(new CruiseBookingRequestMail($request, 'received'));
        }

        $adminEmail = WebsiteSetting::cached()->company_email;
        if ($adminEmail) {
            Mail::to($adminEmail)->send(new CruiseBookingRequestMail($request, 'admin_new'));
        }
    }

    public function notifyStatusChanged(CruiseBookingRequest $request, string $previousStatus): void
    {
        $request->load(['passengers', 'customer']);

        if ($request->customer_id) {
            UserNotification::query()->create([
                'user_id' => $request->customer_id,
                'title' => 'Cruise Request Updated',
                'body' => "Request {$request->reference_number} is now {$request->statusLabel()}.",
            ]);
        }

        if ($request->contact_email) {
            Mail::to($request->contact_email)->send(
                new CruiseBookingRequestMail($request, 'status_changed', $previousStatus),
            );
        }
    }

    public function notifyDocumentUploaded(CruiseBookingRequest $request, string $documentType): void
    {
        $request->load(['customer']);

        if ($request->customer_id) {
            UserNotification::query()->create([
                'user_id' => $request->customer_id,
                'title' => 'Cruise Document Available',
                'body' => "{$documentType} is available for {$request->reference_number}.",
            ]);
        }

        if ($request->contact_email) {
            Mail::to($request->contact_email)->send(
                new CruiseBookingRequestMail($request, 'document_uploaded', documentType: $documentType),
            );
        }
    }
}
