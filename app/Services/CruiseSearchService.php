<?php

namespace App\Services;

use App\Enums\DestinationType;
use App\Models\Cruise;
use App\Models\CruiseCabin;
use App\Models\TravelDestination;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CruiseSearchService
{
    /**
     * @return array<string, string>
     */
    public function regionOptions(): array
    {
        $fromDb = TravelDestination::query()
            ->active()
            ->ofType(DestinationType::CruiseRegion->value)
            ->ordered()
            ->pluck('name', 'name')
            ->all();

        if ($fromDb !== []) {
            return $fromDb;
        }

        return config('cruise.regions', []);
    }

    /**
     * @return array<string, string> value => "January 2026"
     */
    public function departureMonthOptions(): array
    {
        $options = ['' => 'Any Month'];
        $start = now()->startOfMonth();
        $months = (int) config('cruise.departure_month_horizon', 18);

        for ($i = 0; $i < $months; $i++) {
            $date = $start->copy()->addMonths($i);
            $key = $date->format('Y-m');
            $options[$key] = $date->format('F Y');
        }

        return $options;
    }

    /**
     * Distinct cruise lines from active catalog records.
     *
     * @return array<string, string>
     */
    public function cruiseLineOptions(): array
    {
        $fromMaster = app(CruiseLineService::class)->activeOptions();

        if ($fromMaster !== []) {
            return ['' => 'Any Line'] + $fromMaster;
        }

        $lines = Cruise::query()
            ->active()
            ->whereNotNull('cruise_line')
            ->where('cruise_line', '!=', '')
            ->distinct()
            ->orderBy('cruise_line')
            ->pluck('cruise_line', 'cruise_line');

        return ['' => 'Any Line'] + $lines->all();
    }

    /**
     * @return array<string, string>
     */
    public function durationOptions(): array
    {
        return config('cruise.duration_ranges', []);
    }

    public function applyFilters(Builder $query, Request $request): Builder
    {
        $destination = $request->input('destination') ?? $request->input('q');
        if (is_array($destination)) {
            $destination = null;
        }

        if ($line = $request->input('cruise_line')) {
            $query->where('cruise_line', $line);
        }

        if ($month = $request->input('departure_month')) {
            $this->applyDepartureMonthFilter($query, (string) $month);
        }

        $regions = $this->regionOptions();
        $regionKeys = array_filter((array) $request->input('region', []));
        if ($regionKeys !== []) {
            $query->whereIn('cruise_region', $regionKeys);
        } elseif ($destination) {
            $needles = app(CatalogListSearchService::class)->destinationNeedles((string) $destination);
            $matchedRegion = collect($regions)->keys()->first(
                fn ($key) => collect($needles)->contains(
                    fn ($needle) => strcasecmp((string) $key, (string) $needle) === 0
                        || strcasecmp((string) ($regions[$key] ?? ''), (string) $needle) === 0,
                ),
            );

            if ($matchedRegion) {
                $query->where('cruise_region', $matchedRegion);
            } else {
                $query->where(function (Builder $outer) use ($needles) {
                    foreach ($needles as $needle) {
                        $outer->orWhere(function (Builder $q) use ($needle) {
                            $q->search($needle)
                                ->orWhere('cruise_region', 'like', '%'.$needle.'%')
                                ->orWhere('cruise_line', 'like', '%'.$needle.'%')
                                ->orWhere('departure_port', 'like', '%'.$needle.'%')
                                ->orWhere('arrival_port', 'like', '%'.$needle.'%');
                        });
                    }
                });
            }
        }

        if ($request->boolean('featured_only')) {
            $query->featured();
        }

        $durations = array_filter((array) $request->input('duration', []));
        if ($durations !== []) {
            $query->where(function (Builder $outer) use ($durations) {
                foreach ($durations as $duration) {
                    $outer->orWhere(function (Builder $inner) use ($duration) {
                        $this->applyDurationFilter($inner, (string) $duration);
                    });
                }
            });
        } elseif ($duration = $request->input('duration')) {
            $this->applyDurationFilter($query, (string) $duration);
        }

        if ($ship = $request->input('ship')) {
            $query->where('ship_name', 'like', '%'.$ship.'%');
        }

        if ($min = $request->input('min_price')) {
            $min = (float) $min;
            $query->where(function (Builder $q) use ($min) {
                $q->where('price', '>=', $min)
                    ->orWhereHas('cabins', fn (Builder $c) => $c->where('starting_price', '>=', $min));
            });
        }

        if ($max = $request->input('max_price')) {
            $max = (float) $max;
            $query->where(function (Builder $q) use ($max) {
                $q->where('price', '<=', $max)
                    ->orWhereHas('cabins', fn (Builder $c) => $c->where('starting_price', '<=', $max));
            });
        }

        $cabinTypes = app(CatalogListSearchService::class)->cruiseCabinTypesFromRequest($request);
        if ($cabinTypes !== []) {
            $query->whereHas('cabins', fn (Builder $q) => $q->whereIn('cabin_type', $cabinTypes));
        }

        $travelers = app(CatalogListSearchService::class)->totalTravelers($request);
        if ($travelers > 0) {
            $query->whereHas('cabins', fn (Builder $q) => $q->where('max_occupancy', '>=', $travelers));
        }

        return $query;
    }

    public function normalizeDepartureDate(?string $date): ?string
    {
        if (! $date) {
            return null;
        }

        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }

    public function applySort(Builder $query, Request $request): Builder
    {
        return match ($request->input('sort', 'default')) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name' => $query->orderBy('name'),
            default => $query->ordered(),
        };
    }

    /**
     * @return array{min: float, max: float}
     */
    public function priceBounds(): array
    {
        $base = Cruise::query()->active();
        $min = (float) ($base->clone()->min('price') ?? 0);
        $max = (float) ($base->clone()->max('price') ?? 0);
        $cabinMin = (float) (CruiseCabin::query()
            ->whereIn('cruise_id', $base->clone()->select('id'))
            ->min('starting_price') ?? 0);
        $cabinMax = (float) (CruiseCabin::query()
            ->whereIn('cruise_id', $base->clone()->select('id'))
            ->max('starting_price') ?? 0);

        $priceMins = array_filter([$min, $cabinMin], fn ($v) => $v > 0);
        $min = $priceMins === [] ? 0 : min($priceMins);
        $max = max($max, $cabinMax, $min > 0 ? $min : 0);

        if ($max <= 0) {
            $max = 5000;
        }

        return [
            'min' => floor($min),
            'max' => ceil($max),
        ];
    }

    /**
     * @return array<int, string>
     */
    public function preserveQueryKeys(): array
    {
        return [
            'destination', 'q', 'journey-date', 'return-date', 'departure_month', 'cruise_line', 'cruise-class',
            'adult', 'children', 'infant', 'sort', 'min_price', 'max_price',
            'featured_only', 'region', 'duration', 'cabin_type',
            'categories', 'facilities',
        ];
    }

    protected function applyDepartureMonthFilter(Builder $query, string $month): void
    {
        if (! preg_match('/^\d{4}-\d{2}$/', $month)) {
            return;
        }

        $table = $query->getModel()->getTable();

        if (Schema::hasColumn($table, 'available_months')) {
            $query->whereJsonContains('available_months', $month);

            return;
        }

        // Fallback: match cruises updated in that calendar month (until admin tags sailing months).
        $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $query->whereBetween('updated_at', [$start, $start->copy()->endOfMonth()]);
    }

    protected function applyDurationFilter(Builder $query, string $duration): void
    {
        $nightsColumn = Schema::hasColumn($query->getModel()->getTable(), 'duration_nights');

        match ($duration) {
            '1-3' => $nightsColumn
                ? $query->whereBetween('duration_nights', [1, 3])
                : $query->whereBetween('duration_days', [1, 3]),
            '4-7' => $nightsColumn
                ? $query->whereBetween('duration_nights', [4, 7])
                : $query->whereBetween('duration_days', [4, 7]),
            '8-14' => $nightsColumn
                ? $query->whereBetween('duration_nights', [8, 14])
                : $query->whereBetween('duration_days', [8, 14]),
            '15+' => $nightsColumn
                ? $query->where('duration_nights', '>=', 15)
                : $query->where('duration_days', '>=', 15),
            default => null,
        };
    }

    /**
     * @return array<string, mixed>
     */
    public function searchParamsFromRequest(Request $request): array
    {
        return array_filter([
            'destination' => $request->input('destination'),
            'journey-date' => $request->input('journey-date', $request->input('departure_date')),
            'return-date' => $request->input('return-date', $request->input('return_date')),
            'departure_month' => $request->input('departure_month'),
            'cruise_line' => $request->input('cruise_line'),
            'duration' => $request->input('duration'),
            'region' => $request->input('region'),
            'adults' => $request->input('adults', $request->input('adult')),
            'children' => $request->input('children'),
            'infant' => $request->input('infant'),
            'ship' => $request->input('ship'),
            'min_price' => $request->input('min_price'),
            'max_price' => $request->input('max_price'),
            'cabin_type' => $request->input('cabin_type'),
            'cruise-class' => $request->input('cruise-class', $request->input('cruise_class')),
            'featured_only' => $request->boolean('featured_only') ? '1' : null,
            'sort' => $request->input('sort'),
        ], fn ($v) => $v !== null && $v !== '' && $v !== []);
    }
}
