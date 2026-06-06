<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesCatalog;
use App\Models\InsuranceCmsBlock;
use App\Models\TravelInsurance;
use App\Services\CatalogListSearchService;
use App\Services\CatalogMasterDataService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TravelInsuranceController extends Controller
{
    use HandlesCatalog;

    public function __construct(
        protected CatalogListSearchService $catalogListSearch,
    ) {}

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
        return 'pages.publicView.travelInsurance.show';
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

    public function index(Request $request): View
    {
        $items = $this->catalogQuery($request)->paginate(12)->withQueryString();

        return view($this->catalogListView(), $this->catalogListViewData($request, $items));
    }

    public function search(Request $request): RedirectResponse
    {
        return redirect()->route('travelinsurance', $request->query());
    }

    public function book(string $slug): RedirectResponse
    {
        $url = route('travelinsurance.quote.wizard', ['travelInsurance' => $slug]);
        $query = array_filter(request()->only([
            'destination',
            'journey-date',
            'return-date',
            'travelers',
        ]));

        if ($query !== []) {
            $url .= '?'.http_build_query($query);
        }

        return redirect()->to($url);
    }

    /**
     * @return array<string, mixed>
     */
    protected function catalogListViewData(Request $request, $items): array
    {
        return [
            'items' => $items,
            'routePrefix' => $this->catalogRoutePrefix(),
            'filterGroups' => $this->catalogFilterGroups(),
            'planTypeOptions' => config('insurance.plan_types', []),
            'searchQuery' => $this->insuranceSearchQuery($request),
            'activeDestination' => $request->input('destination', $request->input('q')),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function insuranceSearchQuery(Request $request): array
    {
        return array_filter([
            'destination' => $request->input('destination', $request->input('q')),
            'journey-date' => $request->input('journey-date'),
            'return-date' => $request->input('return-date'),
            'travelers' => $request->input('travelers'),
        ]);
    }

    protected function catalogQuery(Request $request): Builder
    {
        $query = TravelInsurance::query()
            ->active()
            ->with(['benefits', 'insuranceTypes', 'coverageTypes']);

        if ($term = $request->input('destination') ?? $request->input('q')) {
            $this->catalogListSearch->applyInsuranceDestinationFilter($query, (string) $term);
        }

        if ($tripDays = $this->catalogListSearch->tripDurationDays($request)) {
            $query->where(function (Builder $q) use ($tripDays) {
                $q->whereNull('duration_days')
                    ->orWhere('duration_days', '>=', $tripDays);
            });
        }

        $planTypes = array_filter((array) $request->input('plan_type', []));
        if ($planTypes !== []) {
            $query->whereIn('plan_type', $planTypes);
        }

        if ($request->boolean('schengen')) {
            $query->where('schengen_covered', true);
        }

        if ($request->boolean('worldwide')) {
            $query->where('worldwide_covered', true);
        }

        if ($request->boolean('featured_only')) {
            $query->where('featured', true);
        }

        app(CatalogMasterDataService::class)->applyMasterFilters($query, $request, 'travelinsurance');

        match ($request->input('sort', 'default')) {
            'price_asc' => $query->orderByRaw('COALESCE(base_premium, price) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(base_premium, price) DESC'),
            'name' => $query->orderBy('name'),
            default => $query->ordered(),
        };

        return $query;
    }

    public function show(string $slug): View
    {
        $item = TravelInsurance::query()
            ->active()
            ->where('slug', $slug)
            ->with(['benefits', 'exclusions', 'galleryImages'])
            ->firstOrFail();

        $related = TravelInsurance::query()
            ->active()
            ->where('id', '!=', $item->id)
            ->ordered()
            ->take(3)
            ->get();

        return view($this->catalogDetailView(), [
            'item' => $item,
            'related' => $related,
            'cmsBlocks' => InsuranceCmsBlock::cachedActive(),
            'routePrefix' => $this->catalogRoutePrefix(),
            'bookableType' => $this->catalogRoutePrefix(),
            'catalogLabel' => $this->catalogLabel(),
        ]);
    }
}
