<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesCatalog;
use App\Models\TourPackage;

class TourPackageController extends Controller
{
    use HandlesCatalog;

    protected function catalogModel(): string
    {
        return TourPackage::class;
    }

    protected function catalogListView(): string
    {
        return 'pages.publicView.tourPackage.tourPackageList';
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
        return 'tourpackage';
    }

    protected function catalogMasterDataKey(): ?string
    {
        return 'tourpackage';
    }
}
