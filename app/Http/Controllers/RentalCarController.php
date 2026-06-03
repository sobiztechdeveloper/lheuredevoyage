<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesCatalog;
use App\Models\RentalCar;

class RentalCarController extends Controller
{
    use HandlesCatalog;

    protected function catalogModel(): string
    {
        return RentalCar::class;
    }

    protected function catalogListView(): string
    {
        return 'pages.publicView.rentalCar.rentalCarList';
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
        return 'rentalcar';
    }

    protected function catalogMasterDataKey(): ?string
    {
        return 'rentalcar';
    }
}
