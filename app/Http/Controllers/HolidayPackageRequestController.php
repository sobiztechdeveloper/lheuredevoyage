<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHolidayPackageRequestRequest;
use App\Services\HolidayPackageRequestNotificationService;
use App\Services\HolidayPackageRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HolidayPackageRequestController extends Controller
{
    public function __construct(
        protected HolidayPackageRequestService $service,
        protected HolidayPackageRequestNotificationService $notifications,
    ) {}

    public function modal(Request $request): View
    {
        $locale = in_array($request->query('locale'), ['en', 'fr', 'nl'], true)
            ? $request->query('locale')
            : app()->getLocale();

        if (in_array($locale, ['en', 'fr', 'nl'], true)) {
            app()->setLocale($locale);
        }

        return view('partials.holiday-package-request.modal', [
            'locale' => $locale,
            'config' => holiday_package_request_config(),
        ]);
    }

    public function store(StoreHolidayPackageRequestRequest $request): JsonResponse
    {
        $locale = $request->input('locale');
        if (in_array($locale, ['en', 'fr', 'nl'], true)) {
            app()->setLocale($locale);
        }

        $record = $this->service->create($request->validated());

        try {
            $this->notifications->notifyAdmin($record);
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json([
            'success' => true,
            'message' => __('holiday_package_request.submitted'),
            'reference' => $record->reference_number,
        ]);
    }
}
