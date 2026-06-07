<?php

namespace App\Services;

use App\Models\FlightSearch;
use Illuminate\Http\Request;
class FlightSearchParamsService
{
    /**
     * @return array<string, mixed>
     */
    public function panelValues(Request $request, ?FlightSearch $search = null): array
    {
        $payload = is_array($search?->search_payload) ? $search->search_payload : [];
        $formTripType = $this->normalizeFormTripType(
            $request->input('flight-type', $request->input('trip_type', $search?->trip_type ?? 'one_way'))
        );

        return [
            'from_destination' => (string) $request->input(
                'from-destination',
                $request->input('from_destination', $search?->from_destination ?? '')
            ),
            'to_destination' => (string) $request->input(
                'to-destination',
                $request->input('to_destination', $search?->to_destination ?? '')
            ),
            'journey_date' => $this->resolveDisplayDate(
                $request,
                'journey-date',
                'journey_date',
                $search?->journey_date
            ),
            'return_date' => $this->resolveDisplayDate(
                $request,
                'return-date',
                'return_date',
                $search?->return_date
            ),
            'flight_type' => $formTripType,
            'trip_type' => $this->formTripTypeToStored($formTripType),
            'adult' => (int) $request->input('adult', $search?->adult ?? 2),
            'children' => (int) $request->input('children', $search?->children ?? 0),
            'infant' => (int) $request->input('infant', $search?->infant ?? 0),
            'cabin_class' => (string) $request->input(
                'cabin_class',
                $request->input('cabin-class', $search?->cabin_class ?? 'economy')
            ),
            'from_departure_id' => (string) $request->input(
                'from_departure_id',
                $payload['from_departure_id'] ?? $payload['departure_id'] ?? ''
            ),
            'to_arrival_id' => (string) $request->input(
                'to_arrival_id',
                $payload['to_arrival_id'] ?? $payload['arrival_id'] ?? ''
            ),
        ];
    }

    /**
     * @param  array<string, mixed>  $panel
     * @return array<string, mixed>
     */
    public function queryParamsFromPanel(array $panel, ?int $flightSearchId = null): array
    {
        $params = [
            'from-destination' => $panel['from_destination'] ?? '',
            'to-destination' => $panel['to_destination'] ?? '',
            'journey-date' => $panel['journey_date'] ?? '',
            'return-date' => $panel['return_date'] ?? '',
            'flight-type' => $panel['flight_type'] ?? 'one-way',
            'adult' => $panel['adult'] ?? 2,
            'children' => $panel['children'] ?? 0,
            'infant' => $panel['infant'] ?? 0,
            'cabin_class' => $panel['cabin_class'] ?? 'economy',
        ];

        if (! empty($panel['from_departure_id'])) {
            $params['from_departure_id'] = $panel['from_departure_id'];
        }

        if (! empty($panel['to_arrival_id'])) {
            $params['to_arrival_id'] = $panel['to_arrival_id'];
        }

        if ($flightSearchId) {
            $params['flight_search'] = $flightSearchId;
        }

        return $params;
    }

    /**
     * @return array<string, mixed>
     */
    public function queryParamsFromRequest(Request $request, ?int $flightSearchId = null): array
    {
        return $this->queryParamsFromPanel($this->panelValues($request), $flightSearchId);
    }

    /**
     * @return array<string, mixed>
     */
    public function queryParamsFromSearch(FlightSearch $search): array
    {
        $panel = $this->panelValues(request(), $search);

        return $this->queryParamsFromPanel($panel, $search->id);
    }

    /**
     * @return array<string, mixed>
     */
    public function searchQueryForView(Request $request, FlightSearch $search): array
    {
        return array_filter(
            $this->queryParamsFromSearch($search),
            fn ($value) => $value !== null && $value !== '' && $value !== []
        );
    }

    public function normalizeFormTripType(mixed $value): string
    {
        return match ((string) $value) {
            'round-way', 'round_trip' => 'round-way',
            'multi-city', 'multi_city' => 'multi-city',
            default => 'one-way',
        };
    }

    public function formTripTypeToStored(string $formTripType): string
    {
        return match ($formTripType) {
            'round-way' => 'round_trip',
            default => 'one_way',
        };
    }

    private function resolveDisplayDate(
        Request $request,
        string $hyphenKey,
        string $underscoreKey,
        mixed $modelDate,
    ): string {
        $raw = $request->input($hyphenKey, $request->input($underscoreKey));

        if ($raw !== null && $raw !== '') {
            $parsed = parse_user_date($raw);

            return $parsed
                ? $parsed->format(config('date.display', 'd/m/Y'))
                : (string) $raw;
        }

        if ($modelDate) {
            return format_date($modelDate);
        }

        return '';
    }
}
