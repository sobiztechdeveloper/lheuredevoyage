<?php

namespace App\Services;

use App\Mail\QuoteMail;
use App\Models\Quote;
use App\Models\UserNotification;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Mail;

class QuoteNotificationService
{
    public function notifySent(Quote $quote): void
    {
        $quote->load(['customer', 'flightBookingRequest']);

        $email = $this->customerEmail($quote);
        if ($email) {
            Mail::to($email)->send(new QuoteMail($quote, 'sent'));
        }

        if ($quote->customer_id) {
            UserNotification::query()->create([
                'user_id' => $quote->customer_id,
                'title' => 'New Quote Available',
                'body' => "Quote {$quote->quote_number} for {$quote->title} is ready to review.",
            ]);
        }
    }

    public function notifyAccepted(Quote $quote): void
    {
        $quote->load(['customer', 'creator', 'flightBookingRequest']);

        $email = $this->customerEmail($quote);
        if ($email) {
            Mail::to($email)->send(new QuoteMail($quote, 'accepted'));
        }

        $adminEmail = WebsiteSetting::cached()->company_email;
        if ($adminEmail) {
            Mail::to($adminEmail)->send(new QuoteMail($quote, 'admin_accepted'));
        }
    }

    public function notifyRejected(Quote $quote): void
    {
        $quote->load(['customer', 'creator', 'flightBookingRequest']);

        $email = $this->customerEmail($quote);
        if ($email) {
            Mail::to($email)->send(new QuoteMail($quote, 'rejected'));
        }

        $adminEmail = WebsiteSetting::cached()->company_email;
        if ($adminEmail) {
            Mail::to($adminEmail)->send(new QuoteMail($quote, 'admin_rejected'));
        }
    }

    public function notifyExpired(Quote $quote): void
    {
        $quote->load(['customer', 'flightBookingRequest']);

        $email = $this->customerEmail($quote);
        if ($email) {
            Mail::to($email)->send(new QuoteMail($quote, 'expired'));
        }

        if ($quote->customer_id) {
            UserNotification::query()->create([
                'user_id' => $quote->customer_id,
                'title' => 'Quote Expired',
                'body' => "Quote {$quote->quote_number} has expired.",
            ]);
        }
    }

    protected function customerEmail(Quote $quote): ?string
    {
        if ($quote->customer?->email) {
            return $quote->customer->email;
        }

        return $quote->flightBookingRequest?->email;
    }
}
