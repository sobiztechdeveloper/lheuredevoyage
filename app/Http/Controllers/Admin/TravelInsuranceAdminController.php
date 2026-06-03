<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesCatalog;
use App\Http\Controllers\Controller;
use App\Models\TravelInsurance;

class TravelInsuranceAdminController extends Controller
{
    use ManagesCatalog;

    protected function catalogModel(): string
    {
        return TravelInsurance::class;
    }

    protected function catalogLabel(): string
    {
        return 'Travel Insurance';
    }

    protected function catalogMasterDataKey(): ?string
    {
        return 'travelinsurance';
    }

    protected function resourceKey(): string
    {
        return 'insurances';
    }

    protected function catalogUploadDirectory(): string
    {
        return 'travel-insurances';
    }

    protected function catalogFields(): array
    {
        return ['coverage'];
    }

    protected function catalogRules(): array
    {
        return [
            'coverage' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }
}
