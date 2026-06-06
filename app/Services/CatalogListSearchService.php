<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CatalogListSearchService
{
    public function tripDurationDays(Request $request): ?int
    {
        $journey = $request->input('journey-date', $request->input('journey_date', $request->input('departure_date')));
        $return = $request->input('return-date', $request->input('return_date'));

        if (! $journey || ! $return) {
            return null;
        }

        try {
            $start = Carbon::parse($journey)->startOfDay();
            $end = Carbon::parse($return)->startOfDay();

            if ($end->lt($start)) {
                return null;
            }

            return (int) $start->diffInDays($end) + 1;
        } catch (\Throwable) {
            return null;
        }
    }

    public function totalTravelers(Request $request): int
    {
        if ($request->filled('travelers')) {
            return max(1, (int) $request->input('travelers'));
        }

        return max(
            1,
            (int) $request->input('adult', 1)
            + (int) $request->input('children', 0)
            + (int) $request->input('infant', 0),
        );
    }

    /**
     * Map homepage cruise-class radio labels to catalog cabin_type keys.
     *
     * @return list<string>
     */
    public function cruiseCabinTypesFromRequest(Request $request): array
    {
        $cabinTypes = array_filter((array) $request->input('cabin_type', []));

        if ($cabinTypes !== []) {
            return $cabinTypes;
        }

        $cruiseClass = $request->input('cruise-class', $request->input('cruise_class'));

        if (! $cruiseClass) {
            return [];
        }

        return match (Str::lower((string) $cruiseClass)) {
            'in cabin', 'interior' => ['interior'],
            'in chair', 'ocean view', 'ocean_view' => ['ocean_view'],
            'in first class', 'suite', 'first class' => ['suite'],
            'balcony' => ['balcony'],
            default => [],
        };
    }

    public function applyInsuranceDestinationFilter(Builder $query, string $term): Builder
    {
        $needles = $this->destinationNeedles($term);

        return $query->where(function (Builder $outer) use ($needles) {
            foreach ($needles as $needle) {
                $like = '%'.$needle.'%';

                $outer->orWhere(function (Builder $inner) use ($like, $needle) {
                    $inner->search($needle)
                        ->orWhere('covered_regions', 'like', $like)
                        ->orWhere('covered_countries', 'like', $like)
                        ->orWhere('location', 'like', $like);
                });
            }
        });
    }

    public function matchesDestinationTerm(string $haystack, string $needle): bool
    {
        $haystack = Str::lower(trim($haystack));
        $needle = Str::lower(trim($needle));

        if ($needle === '' || $haystack === '') {
            return false;
        }

        return str_contains($haystack, $needle) || str_contains($needle, $haystack);
    }

    /**
     * @return list<string>
     */
    public function destinationNeedles(string $term): array
    {
        $term = trim($term);

        if ($term === '') {
            return [];
        }

        $needles = [$term];

        if (str_contains($term, ',')) {
            foreach (explode(',', $term) as $part) {
                $part = trim($part);
                if ($part !== '') {
                    $needles[] = $part;
                }
            }
        }

        if (preg_match('/\(([^)]+)\)/', $term, $matches)) {
            $needles[] = trim($matches[1]);
        }

        return array_values(array_unique(array_filter($needles)));
    }
}
