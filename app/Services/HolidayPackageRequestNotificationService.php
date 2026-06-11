<?php

namespace App\Services;

use App\Mail\HolidayPackageRequestMail;
use App\Models\HolidayPackageRequest;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Mail;

class HolidayPackageRequestNotificationService
{
    public function notifyAdmin(HolidayPackageRequest $request): void
    {
        $email = config('holiday_package_request.admin_email')
            ?: WebsiteSetting::cached()->company_email;

        if (! $email) {
            return;
        }

        Mail::to($email)->send(new HolidayPackageRequestMail($request));
    }
}
