<?php

namespace App\Services;

use App\Mail\FlightBookingRequestMail;
use App\Models\FlightBookingRequest;
use App\Models\UserNotification;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Mail;

class FlightBookingNotificationService
{
    public function notifyCreated(FlightBookingRequest $request): void
    {
        $request->load(['passengers', 'user']);

        if ($request->user_id) {
            UserNotification::query()->create([
                'user_id' => $request->user_id,
                'title' => 'Flight Request Submitted',
                'body' => "Your flight request {$request->booking_reference} has been received. We will respond within 24 hours.",
            ]);
        }

        if ($request->email) {
            Mail::to($request->email)->send(new FlightBookingRequestMail($request, 'received'));
        }

        $adminEmail = WebsiteSetting::cached()->company_email;
        if ($adminEmail) {
            Mail::to($adminEmail)->send(new FlightBookingRequestMail($request, 'admin_new'));
        }
    }

    public function notifyStatusChanged(FlightBookingRequest $request, string $previousStatus): void
    {
        $request->load(['passengers', 'user']);

        if ($request->user_id) {
            UserNotification::query()->create([
                'user_id' => $request->user_id,
                'title' => 'Flight Request Updated',
                'body' => "Request {$request->booking_reference} is now {$request->statusLabel()}.",
            ]);
        }

        if ($request->email) {
            Mail::to($request->email)->send(
                new FlightBookingRequestMail($request, 'status_changed', $previousStatus),
            );
        }
    }

    public function notifyDocumentUploaded(FlightBookingRequest $request, string $documentType): void
    {
        $request->load(['user']);

        if ($request->user_id) {
            UserNotification::query()->create([
                'user_id' => $request->user_id,
                'title' => 'Flight Document Available',
                'body' => "A {$documentType} is available for {$request->booking_reference}.",
            ]);
        }

        if ($request->email) {
            Mail::to($request->email)->send(
                new FlightBookingRequestMail($request, 'document_uploaded', documentType: $documentType),
            );
        }
    }
}
