<?php

namespace App\Http\Controllers\Concerns;

use App\Services\CatalogMasterDataService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

trait HandlesCatalog
{
    abstract protected function catalogModel(): string;

    abstract protected function catalogListView(): string;

    abstract protected function catalogDetailView(): string;

    abstract protected function catalogBookingView(): string;

    abstract protected function catalogRoutePrefix(): string;

    public function index(Request $request): View
    {
        $items = $this->catalogQuery($request)->paginate(12)->withQueryString();

        return view($this->catalogListView(), [
            'items' => $items,
            'routePrefix' => $this->catalogRoutePrefix(),
            'filterGroups' => $this->catalogFilterGroups(),
            'searchQuery' => $this->catalogSearchQuery($request),
        ]);
    }

    public function search(Request $request): RedirectResponse
    {
        return redirect()->route($this->catalogRoutePrefix(), $request->query());
    }

    public function show(string $slug): View
    {
        $item = $this->findCatalogItem($slug);

        return view($this->catalogDetailView(), $this->catalogViewData($item));
    }

    public function book(string $slug): View
    {
        $item = $this->findCatalogItem($slug);

        return view($this->catalogBookingView(), array_merge(
            $this->catalogViewData($item),
            ['travelers' => $this->catalogBookingTravelers(request())],
        ));
    }

    /**
     * @return array{adult: int, children: int, infant: int}
     */
    protected function catalogBookingTravelers(Request $request): array
    {
        return [
            'adult' => max(1, (int) $request->input('adult', 2)),
            'children' => max(0, (int) $request->input('children', 0)),
            'infant' => max(0, (int) $request->input('infant', 0)),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function catalogViewData($item): array
    {
        return [
            'item' => $item,
            'routePrefix' => $this->catalogRoutePrefix(),
            'bookableType' => $this->catalogRoutePrefix(),
            'catalogLabel' => $this->catalogLabel(),
        ];
    }

    protected function catalogLabel(): string
    {
        return match ($this->catalogRoutePrefix()) {
            'hotel' => 'Hotel',
            'tourpackage' => 'Tour Package',
            'cruise' => 'Cruise',
            'rentalcar' => 'Rental Car',
            'travelinsurance' => 'Travel Insurance',
            default => 'Listing',
        };
    }

    protected function catalogQuery(Request $request): Builder
    {
        $model = $this->catalogModel();

        $query = $model::query()->active()->latest();

        if ($term = $request->input('destination') ?? $request->input('q')) {
            $query->search($term);
        }

        if ($key = $this->catalogMasterDataKey()) {
            app(CatalogMasterDataService::class)->applyMasterFilters($query, $request, $key);
        }

        $this->applyCatalogSort($query, $request);

        return $query;
    }

    protected function applyCatalogSort(Builder $query, Request $request): void
    {
        match ($request->input('sort', 'default')) {
            'price_asc' => $query->reorder()->orderBy('price', 'asc'),
            'price_desc' => $query->reorder()->orderBy('price', 'desc'),
            'name' => $query->reorder()->orderBy('name', 'asc'),
            default => $query,
        };
    }

    /**
     * @return array<string, mixed>
     */
    protected function catalogSearchQuery(Request $request): array
    {
        return array_filter(
            $request->query(),
            fn ($value) => $value !== null && $value !== '' && $value !== [],
        );
    }

    protected function catalogMasterDataKey(): ?string
    {
        return null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function catalogFilterGroups(): array
    {
        $key = $this->catalogMasterDataKey();

        return $key
            ? app(CatalogMasterDataService::class)->filterGroupsForCatalog($key)
            : [];
    }

    protected function findCatalogItem(string $slug): Model
    {
        return $this->catalogModel()::query()
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();
    }
}
