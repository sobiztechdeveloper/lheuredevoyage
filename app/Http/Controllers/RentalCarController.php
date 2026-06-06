<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesCatalog;
use App\Models\RentalCar;
use App\Services\CatalogListSearchService;
use App\Services\CatalogMasterDataService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class RentalCarController extends Controller
{
    use HandlesCatalog;

    public function __construct(
        protected CatalogListSearchService $catalogListSearch,
    ) {}

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

    protected function catalogQuery(Request $request): Builder
    {
        $query = RentalCar::query()->active();

        $pickup = trim((string) ($request->input('destination') ?? $request->input('q') ?? ''));
        $dropoff = trim((string) $request->input('dropoff'));

        if ($pickup !== '' || $dropoff !== '') {
            $query->where(function (Builder $outer) use ($pickup, $dropoff) {
                if ($pickup !== '') {
                    foreach ($this->catalogListSearch->destinationNeedles($pickup) as $needle) {
                        $outer->orWhere(function (Builder $inner) use ($needle) {
                            $inner->search($needle);
                        });
                    }
                }

                if ($dropoff !== '') {
                    foreach ($this->catalogListSearch->destinationNeedles($dropoff) as $needle) {
                        $outer->orWhere(function (Builder $inner) use ($needle) {
                            $inner->search($needle);
                        });
                    }
                }
            });
        }

        $travelers = $this->catalogListSearch->totalTravelers($request);
        if ($travelers > 1 && \Illuminate\Support\Facades\Schema::hasColumn('rental_cars', 'passenger_capacity')) {
            $query->where(function (Builder $q) use ($travelers) {
                $q->whereNull('passenger_capacity')
                    ->orWhere('passenger_capacity', '>=', $travelers)
                    ->orWhere('seats', '>=', $travelers);
            });
        }

        app(CatalogMasterDataService::class)->applyMasterFilters($query, $request, 'rentalcar');
        $this->applyCatalogSort($query, $request);

        return $query;
    }

    /**
     * @return array<string, mixed>
     */
    protected function catalogSearchQuery(Request $request): array
    {
        return array_filter([
            'destination' => $request->input('destination', $request->input('q')),
            'pickup-date' => $request->input('pickup-date'),
            'pick-up-time' => $request->input('pick-up-time'),
            'dropoff' => $request->input('dropoff'),
            'return-date' => $request->input('return-date'),
            'drop-off-time' => $request->input('drop-off-time'),
            'sort' => $request->input('sort'),
        ], fn ($value) => $value !== null && $value !== '' && $value !== []);
    }
}
