<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesCatalog;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HotelController extends Controller
{
    use HandlesCatalog;

    public function __construct(
        protected HotelSearchController $hotelSearchController,
    ) {}

    public function index(Request $request): View
    {
        return $this->hotelSearchController->hotelsPage($request);
    }

    public function search(Request $request): View
    {
        return $this->hotelSearchController->hotelsPage($request);
    }

    public function show(string $slug): View
    {
        $hotel = Hotel::query()
            ->active()
            ->where('slug', $slug)
            ->with([
                'facilities',
                'sports',
                'wellnesses',
                'beachTypes',
                'roomFacilities',
                'mealPlans',
                'activeRooms',
            ])
            ->firstOrFail();

        $searchParams = request()->query();
        if (request()->filled('hotel_search')) {
            $search = \App\Models\HotelSearch::query()->find(request()->integer('hotel_search'));
            if ($search) {
                $searchParams = array_merge($search->bookingQueryParams(), $searchParams);
            }
        }

        return view('pages.publicView.hotel.hotelDetail', [
            'hotel' => $hotel,
            'routePrefix' => $this->catalogRoutePrefix(),
            'searchParams' => $searchParams,
        ]);
    }

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
        return 'pages.publicView.hotel.hotelDetail';
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
