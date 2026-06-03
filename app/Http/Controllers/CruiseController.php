<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesCatalog;
use App\Models\Cruise;

class CruiseController extends Controller
{
    use HandlesCatalog;

    protected function catalogModel(): string
    {
        return Cruise::class;
    }

    protected function catalogListView(): string
    {
        return 'pages.publicView.cruise.cruiseList';
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
        return 'cruise';
    }

    protected function catalogMasterDataKey(): ?string
    {
        return 'cruise';
    }
}
