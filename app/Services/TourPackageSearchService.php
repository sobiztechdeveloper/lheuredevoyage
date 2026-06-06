<?php

namespace App\Services;

use App\Enums\DestinationType;
use App\Models\TourPackage;
use App\Models\TravelDestination;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class TourPackageSearchService
{
    /**
     * @return array<string, string>
     */
    public function countryOptions(): array
    {
        $fromDb = TravelDestination::query()
            ->active()
            ->ofType(DestinationType::Country->value)
            ->ordered()
            ->pluck('name', 'name')
            ->all();

        if ($fromDb !== []) {
            return ['' => 'Select destination country'] + $fromDb;
        }

        return ['' => 'Select destination country'] + [
            'Switzerland' => 'Switzerland',
            'France' => 'France',
            'Italy' => 'Italy',
            'Germany' => 'Germany',
            'Spain' => 'Spain',
            'United Kingdom' => 'United Kingdom',
            'Dubai' => 'Dubai',
            'Thailand' => 'Thailand',
            'Japan' => 'Japan',
            'Maldives' => 'Maldives',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function holidayTypeOptions(): array
    {
        return ['' => 'Select holiday type'] + config('tourpackage.holiday_types', []);
    }

    /**
     * @return array<string, string> value => "January 2027"
     */
    public function travelMonthOptions(): array
    {
        $options = ['' => 'Select travel month'];
        $start = now()->startOfMonth();
        $months = (int) config('tourpackage.travel_month_horizon', 18);

        for ($i = 0; $i < $months; $i++) {
            $date = $start->copy()->addMonths($i);
            $key = $date->format('Y-m');
            $options[$key] = $date->format('F Y');
        }

        return $options;
    }

    /**
     * @return array<string, string>
     */
    public function durationOptions(): array
    {
        return config('tourpackage.duration_ranges', []);
    }

    /**
     * @return array{min: float, max: float}
     */
    public function priceBounds(): array
    {
        $min = (float) (TourPackage::query()->active()->min('price') ?? 0);
        $max = (float) (TourPackage::query()->active()->max('price') ?? 0);

        if ($max <= 0) {
            $max = 5000;
        }

        return [
            'min' => floor($min),
            'max' => ceil(max($max, $min)),
        ];
    }

    public function applyFilters(Builder $query, Request $request): Builder
    {
        $countries = array_values(array_filter(array_merge(
            array_filter((array) $request->input('country', [])),
            array_filter([trim((string) ($request->input('destination') ?? $request->input('q') ?? ''))]),
        )));

        if ($countries !== []) {
            $query->where(function (Builder $outer) use ($countries) {
                foreach ($countries as $country) {
                    $needles = app(CatalogListSearchService::class)->destinationNeedles((string) $country);
                    foreach ($needles as $needle) {
                        $outer->orWhere(function (Builder $inner) use ($needle) {
                            $inner->where('country', 'like', '%'.$needle.'%')
                                ->orWhere('destination', 'like', '%'.$needle.'%')
                                ->orWhere('location', 'like', '%'.$needle.'%')
                                ->orWhere('title', 'like', '%'.$needle.'%')
                                ->orWhere('name', 'like', '%'.$needle.'%');
                        });
                    }
                }
            });
        }

        $holidayTypes = array_values(array_filter((array) $request->input('holiday_type', [])));
        if ($holidayTypes === [] && is_string($request->input('holiday_type')) && $request->filled('holiday_type')) {
            $holidayTypes = [$request->input('holiday_type')];
        }

        if ($holidayTypes !== []) {
            $query->whereIn('holiday_type', $holidayTypes);
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
        }

        if ($min = $request->input('min_price')) {
            $query->where('price', '>=', (float) $min);
        }

        if ($max = $request->input('max_price')) {
            $query->where('price', '<=', (float) $max);
        }

        return $query;
    }

    protected function applyDurationFilter(Builder $query, string $duration): void
    {
        if (! Schema::hasColumn('tour_packages', 'duration_days')) {
            return;
        }

        match ($duration) {
            '1-3' => $query->whereBetween('duration_days', [1, 3]),
            '4-7' => $query->whereBetween('duration_days', [4, 7]),
            '8-14' => $query->whereBetween('duration_days', [8, 14]),
            '15+' => $query->where('duration_days', '>=', 15),
            default => null,
        };
    }

    /**
     * @return array<string, mixed>
     */
    public function searchParamsFromRequest(Request $request): array
    {
        return array_filter([
            'destination' => $request->input('destination', $request->input('q')),
            'holiday_type' => $request->input('holiday_type'),
            'travel_month' => $request->input('travel_month'),
            'adult' => $request->input('adult'),
            'children' => $request->input('children'),
            'infant' => $request->input('infant'),
            'duration' => $request->input('duration'),
            'min_price' => $request->input('min_price'),
            'max_price' => $request->input('max_price'),
            'sort' => $request->input('sort'),
        ], fn ($v) => $v !== null && $v !== '' && $v !== []);
    }

    /**
     * @return list<string>
     */
    public function preserveQueryKeys(): array
    {
        return [
            'destination', 'q', 'country', 'holiday_type', 'travel_month',
            'adult', 'children', 'infant', 'sort',
            'duration', 'min_price', 'max_price',
        ];
    }
}
