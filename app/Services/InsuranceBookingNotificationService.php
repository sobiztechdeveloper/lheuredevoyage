<?php

namespace App\Services;

use App\Mail\InsuranceBookingRequestMail;
use App\Models\InsuranceBookingRequest;
use App\Models\UserNotification;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Mail;

class InsuranceBookingNotificationService
{
    public function notifyCreated(InsuranceBookingRequest $request): void
    {
        $request->load(['travelers', 'travelInsurance', 'customer']);

        if ($request->customer_id) {
            UserNotification::query()->create([
                'user_id' => $request->customer_id,
                'title' => 'Insurance Request Submitted',
                'body' => "Your insurance request {$request->reference_number} has been received.",
            ]);
        }

        if ($request->contact_email) {
            Mail::to($request->contact_email)->send(new InsuranceBookingRequestMail($request, 'received'));
        }

        $adminEmail = WebsiteSetting::cached()->company_email;
        if ($adminEmail) {
            Mail::to($adminEmail)->send(new InsuranceBookingRequestMail($request, 'admin_new'));
        }
    }

    public function notifyStatusChanged(InsuranceBookingRequest $request, string $previousStatus): void
    {
        $request->load(['travelers', 'customer']);

        if ($request->customer_id) {
            UserNotification::query()->create([
                'user_id' => $request->customer_id,
                'title' => 'Insurance Request Updated',
                'body' => "Request {$request->reference_number} is now {$request->statusLabel()}.",
            ]);
        }

        if ($request->contact_email) {
            Mail::to($request->contact_email)->send(
                new InsuranceBookingRequestMail($request, 'status_changed', $previousStatus),
            );
        }
    }

    public function notifyDocumentUploaded(InsuranceBookingRequest $request, string $documentType): void
    {
        $request->load(['customer']);

        if ($request->customer_id) {
            UserNotification::query()->create([
                'user_id' => $request->customer_id,
                'title' => 'Insurance Document Available',
                'body' => "{$documentType} is available for {$request->reference_number}.",
            ]);
        }

        if ($request->contact_email) {
            Mail::to($request->contact_email)->send(
                new InsuranceBookingRequestMail($request, 'document_uploaded', documentType: $documentType),
            );
        }
    }
}
