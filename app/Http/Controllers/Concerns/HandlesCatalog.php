<?php

namespace App\Http\Controllers\Concerns;

use App\Services\CatalogMasterDataService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
        $items = $this->catalogQuery($request)->paginate(12);

        return view($this->catalogListView(), [
            'items' => $items,
            'routePrefix' => $this->catalogRoutePrefix(),
            'filterGroups' => $this->catalogFilterGroups(),
        ]);
    }

    public function search(Request $request): View
    {
        return $this->index($request);
    }

    public function show(string $slug): View
    {
        $item = $this->findCatalogItem($slug);

        return view($this->catalogDetailView(), $this->catalogViewData($item));
    }

    public function book(string $slug): View
    {
        $item = $this->findCatalogItem($slug);

        return view($this->catalogBookingView(), $this->catalogViewData($item));
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

        return $query;
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
