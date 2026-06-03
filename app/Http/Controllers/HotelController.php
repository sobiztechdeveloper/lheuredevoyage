<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesCatalog;
use App\Models\Hotel;

class HotelController extends Controller
{
    use HandlesCatalog;

    protected function catalogModel(): string
    {
        return Hotel::class;
    }

    protected function catalogListView(): string
    {
        return 'pages.publicView.hotel.hotelList';
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
        return 'hotel';
    }

    protected function catalogMasterDataKey(): ?string
    {
        return 'hotel';
    }
}
