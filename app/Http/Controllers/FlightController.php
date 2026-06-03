<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesCatalog;
use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FlightController extends Controller
{
    use HandlesCatalog;

    public function __construct(
        protected FlightSearchController $flightSearchController,
    ) {}

    /**
     * Flight search results page (/flights) with filters and sort.
     */
    public function index(Request $request): View
    {
        return $this->flightSearchController->flightsPage($request);
    }

    public function search(Request $request): View
    {
        return $this->flightSearchController->flightsPage($request);
    }

    protected function catalogModel(): string
    {
        return Flight::class;
    }

    protected function catalogListView(): string
    {
        return 'pages.publicView.flight.flightList';
    }

    protected function catalogDetailView(): string
    {
        return 'pages.publicView.flight.flightDetail';
    }

    protected function catalogBookingView(): string
    {
        return 'pages.publicView.flight.flightBooking';
    }

    protected function catalogRoutePrefix(): string
    {
        return 'flight';
    }
}
