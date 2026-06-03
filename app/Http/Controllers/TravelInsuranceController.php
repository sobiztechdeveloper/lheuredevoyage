<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesCatalog;
use App\Models\TravelInsurance;

class TravelInsuranceController extends Controller
{
    use HandlesCatalog;

    protected function catalogModel(): string
    {
        return TravelInsurance::class;
    }

    protected function catalogListView(): string
    {
        return 'pages.publicView.travelInsurance.travelInsuranceList';
    }

    protected function catalogDetailView(): string
    {
        return 'pages.publicView.catalog.detail';
    }

    protected function catalogBookingView(): string
    {
        return 'pages.publicView.catalog.booking';
    }

    protected function catalogRoutePrefix(): string
    {
        return 'travelinsurance';
    }

    protected function catalogMasterDataKey(): ?string
    {
        return 'travelinsurance';
    }
}
