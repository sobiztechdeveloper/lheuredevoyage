<?php

namespace App\Services;

use App\Mail\CarBookingRequestMail;
use App\Models\CarBookingRequest;
use App\Models\UserNotification;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Mail;

class CarBookingNotificationService
{
    public function notifyCreated(CarBookingRequest $request): void
    {
        $request->load(['drivers', 'rentalCar', 'customer']);

        if ($request->customer_id) {
            UserNotification::query()->create([
                'user_id' => $request->customer_id,
                'title' => 'Car Request Submitted',
                'body' => "Your car request {$request->reference_number} has been received.",
            ]);
        }

        if ($request->contact_email) {
            Mail::to($request->contact_email)->send(new CarBookingRequestMail($request, 'received'));
        }

        $adminEmail = WebsiteSetting::cached()->company_email;
        if ($adminEmail) {
            Mail::to($adminEmail)->send(new CarBookingRequestMail($request, 'admin_new'));
        }
    }

    public function notifyStatusChanged(CarBookingRequest $request, string $previousStatus): void
    {
        $request->load(['drivers', 'customer']);

        if ($request->customer_id) {
            UserNotification::query()->create([
                'user_id' => $request->customer_id,
                'title' => 'Car Request Updated',
                'body' => "Request {$request->reference_number} is now {$request->statusLabel()}.",
            ]);
        }

        if ($request->contact_email) {
            Mail::to($request->contact_email)->send(
                new CarBookingRequestMail($request, 'status_changed', $previousStatus),
            );
        }
    }

    public function notifyDocumentUploaded(CarBookingRequest $request, string $documentType): void
    {
        $request->load(['customer']);

        if ($request->customer_id) {
            UserNotification::query()->create([
                'user_id' => $request->customer_id,
                'title' => 'Car Document Available',
                'body' => "{$documentType} is available for {$request->reference_number}.",
            ]);
        }

        if ($request->contact_email) {
            Mail::to($request->contact_email)->send(
                new CarBookingRequestMail($request, 'document_uploaded', documentType: $documentType),
            );
        }
    }
}
