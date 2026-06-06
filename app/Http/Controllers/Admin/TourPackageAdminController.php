<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesCatalog;
use App\Http\Controllers\Controller;
use App\Models\TourPackage;

class TourPackageAdminController extends Controller
{
    use ManagesCatalog;

    protected function catalogModel(): string
    {
        return TourPackage::class;
    }

    protected function catalogLabel(): string
    {
        return 'Tour Package';
    }

    protected function catalogMasterDataKey(): ?string
    {
        return 'tourpackage';
    }

    protected function resourceKey(): string
    {
        return 'packages';
    }

    protected function catalogUploadDirectory(): string
    {
        return 'tour-packages';
    }

    protected function catalogFields(): array
    {
        return ['destination', 'country', 'holiday_type', 'duration', 'duration_days', 'duration_nights', 'included_services'];
    }

    protected function catalogRules(): array
    {
        $holidayTypes = array_keys(config('tourpackage.holiday_types', []));

        return [
            'destination' => ['nullable', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'holiday_type' => ['required', 'string', 'in:'.implode(',', $holidayTypes)],
            'duration' => ['nullable', 'string', 'max:100'],
            'duration_days' => ['nullable', 'integer', 'min:1', 'max:365'],
            'duration_nights' => ['nullable', 'integer', 'min:0', 'max:365'],
            'included_services' => ['nullable', 'array'],
            'included_services.*' => ['string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }
}
