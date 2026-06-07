<?php

namespace App\Services;

use App\Exceptions\SerpApi\SerpApiException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FlightService
{
    protected string $baseUrl = 'https://serpapi.com/search.json';

    public function isConfigured(): bool
    {
        return filled(config('services.serpapi.key'));
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function searchAirports(string $query): array
    {
        $query = trim($query);

        if (mb_strlen($query) < 2) {
            return [];
        }

        $cacheKey = 'serpapi.flight_autocomplete.'.md5(mb_strtolower($query));

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($query) {
            $response = $this->request([
                'engine' => 'google_flights_autocomplete',
                'q' => $query,
                'hl' => config('services.serpapi.hl', 'en'),
                'gl' => config('services.serpapi.gl', 'us'),
            ]);

            return $this->mapAutocompleteSuggestions($response['suggestions'] ?? []);
        });
    }

    /**
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     */
    public function searchFlights(array $params): array
    {
        $departureId = $params['departure_id'] ?? $this->resolveLocationId((string) ($params['from_destination'] ?? ''));
        $arrivalId = $params['arrival_id'] ?? $this->resolveLocationId((string) ($params['to_destination'] ?? ''));

        if ($departureId === '' || $arrivalId === '') {
            throw SerpApiException::fromResponse('Please select valid departure and arrival airports.');
        }

        $tripType = $params['trip_type'] ?? 'one_way';
        $outboundDate = normalize_user_date(
            $params['outbound_date'] ?? $params['journey_date'] ?? null
        ) ?: now()->addDays(14)->toDateString();
        $query = [
            'engine' => 'google_flights',
            'departure_id' => $departureId,
            'arrival_id' => $arrivalId,
            'outbound_date' => $outboundDate,
            'adults' => (int) ($params['adults'] ?? $params['adult'] ?? 1),
            'children' => (int) ($params['children'] ?? 0),
            'currency' => $params['currency'] ?? config('services.serpapi.currency', 'USD'),
            'hl' => $params['hl'] ?? config('services.serpapi.hl', 'en'),
            'gl' => $params['gl'] ?? config('services.serpapi.gl', 'us'),
            'type' => $tripType === 'round_trip' ? '1' : '2',
            'travel_class' => $this->mapTravelClass($params['travel_class'] ?? $params['cabin_class'] ?? 'economy'),
        ];

        if ($tripType === 'round_trip') {
            $returnDate = normalize_user_date($params['return_date'] ?? null)
                ?: (parse_user_date($outboundDate)?->copy()->addDays(7)->format('Y-m-d')
                    ?? now()->addDays(21)->toDateString());

            $query['return_date'] = $returnDate;
        }

        if (! empty($params['infant'])) {
            $query['infants_on_lap'] = (int) $params['infant'];
        }

        $response = $this->request($query);

        $searchCabinClass = (string) ($params['cabin_class'] ?? 'economy');

        return [
            'flights' => $this->mapFlightOffers($response, $searchCabinClass),
            'raw' => $response,
            'departure_id' => $departureId,
            'arrival_id' => $arrivalId,
        ];
    }

    public function resolveLocationId(string $value): string
    {
        $value = trim($value);

        if ($value === '') {
            return '';
        }

        if (preg_match('/\(([A-Z]{3})\)\s*$/', $value, $matches)) {
            return $matches[1];
        }

        if (preg_match('/^\/[mg]\//', $value)) {
            return $value;
        }

        if (preg_match('/^[A-Z]{3}$/', $value)) {
            return $value;
        }

        $suggestions = $this->searchAirports($value);

        return (string) ($suggestions[0]['serpapi_id'] ?? $suggestions[0]['code'] ?? '');
    }

    /**
     * @param  array<int, array<string, mixed>>  $suggestions
     * @return list<array<string, mixed>>
     */
    protected function mapAutocompleteSuggestions(array $suggestions): array
    {
        $items = [];

        foreach ($suggestions as $suggestion) {
            if (! empty($suggestion['airports']) && is_array($suggestion['airports'])) {
                foreach ($suggestion['airports'] as $airport) {
                    $items[] = $this->formatAirportSuggestion($airport, $suggestion);

                    if (count($items) >= 12) {
                        return $items;
                    }
                }

                continue;
            }

            $items[] = [
                'name' => (string) ($suggestion['name'] ?? ''),
                'code' => $this->extractSuggestionCode($suggestion),
                'city' => (string) ($suggestion['name'] ?? ''),
                'country' => $this->extractCountry((string) ($suggestion['description'] ?? '')),
                'serpapi_id' => (string) ($suggestion['id'] ?? ''),
                'text' => (string) ($suggestion['name'] ?? ''),
                'airport_label' => (string) ($suggestion['name'] ?? ''),
                'label' => trim(((string) ($suggestion['name'] ?? '')).' — '.((string) ($suggestion['description'] ?? ''))),
            ];

            if (count($items) >= 12) {
                break;
            }
        }

        return $items;
    }

    /**
     * @param  array<string, mixed>  $airport
     * @param  array<string, mixed>  $parent
     * @return array<string, mixed>
     */
    protected function formatAirportSuggestion(array $airport, array $parent): array
    {
        $code = (string) ($airport['id'] ?? '');
        $name = (string) ($airport['name'] ?? '');
        $city = (string) ($airport['city'] ?? $parent['name'] ?? '');
        $country = $this->extractCountry((string) ($parent['description'] ?? ''));

        return [
            'name' => $name,
            'code' => $code,
            'city' => $city,
            'country' => $country,
            'serpapi_id' => $code !== '' ? $code : (string) ($airport['city_id'] ?? $parent['id'] ?? ''),
            'text' => $code !== '' ? "{$name} ({$code})" : $name,
            'airport_label' => $code !== '' ? "{$name} ({$code})" : $name,
            'label' => trim("{$name} — {$city}, {$country}"),
        ];
    }

    /**
     * @param  array<string, mixed>  $response
     * @return list<array<string, mixed>>
     */
    protected function mapFlightOffers(array $response, string $searchCabinClass = 'economy'): array
    {
        $offers = array_merge(
            $response['best_flights'] ?? [],
            $response['other_flights'] ?? [],
        );

        if ($offers === [] && isset($response['error'])) {
            throw SerpApiException::fromResponse((string) $response['error']);
        }

        $mapped = [];

        foreach ($offers as $index => $offer) {
            $segments = $offer['flights'] ?? [];

            if ($segments === []) {
                continue;
            }

            $first = $segments[0];
            $last = $segments[array_key_last($segments)];
            $stops = max(count($segments) - 1, count($offer['layovers'] ?? []));

            $mapped[] = [
                'external_offer_id' => 'serpapi-'.md5(json_encode($offer).$index),
                'airline' => (string) ($offer['airline'] ?? $first['airline'] ?? 'Airline'),
                'airline_logo' => (string) ($offer['airline_logo'] ?? $first['airline_logo'] ?? ''),
                'flight_number' => (string) ($first['flight_number'] ?? ''),
                'from_destination' => (string) ($first['departure_airport']['name'] ?? $first['departure_airport']['id'] ?? ''),
                'to_destination' => (string) ($last['arrival_airport']['name'] ?? $last['arrival_airport']['id'] ?? ''),
                'departure_at' => (string) ($first['departure_airport']['time'] ?? now()->toDateTimeString()),
                'arrival_at' => (string) ($last['arrival_airport']['time'] ?? now()->toDateTimeString()),
                'duration' => $this->formatDuration((int) ($offer['total_duration'] ?? $first['duration'] ?? 0)),
                'stops' => $stops,
                'cabin_class' => $searchCabinClass,
                'price' => (float) ($offer['price'] ?? 0),
                'currency' => (string) ($response['search_parameters']['currency'] ?? config('services.serpapi.currency', 'USD')),
                'refundable_type' => 'as_per_rules',
                'baggage_kg' => 0,
                'deep_link' => $this->extractDeepLink($offer),
                'raw_offer' => $offer,
            ];
        }

        return $mapped;
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    protected function request(array $query): array
    {
        if (! $this->isConfigured()) {
            throw SerpApiException::notConfigured();
        }

        $query['api_key'] = config('services.serpapi.key');

        try {
            $response = Http::timeout((int) config('services.serpapi.timeout', 60))
                ->get($this->baseUrl, $query)
                ->throw()
                ->json();
        } catch (RequestException $exception) {
            $body = $exception->response?->json() ?? [];
            $message = (string) ($body['error'] ?? $exception->getMessage());

            Log::error('SerpAPI request failed', [
                'engine' => $query['engine'] ?? null,
                'status' => $exception->response?->status(),
                'message' => $message,
            ]);

            if ($exception->response?->status() === 429) {
                throw SerpApiException::fromResponse('Rate limit exceeded', 429);
            }

            throw SerpApiException::fromResponse($message, (int) $exception->response?->status());
        } catch (\Throwable $exception) {
            Log::error('SerpAPI request error', [
                'engine' => $query['engine'] ?? null,
                'message' => $exception->getMessage(),
            ]);

            throw SerpApiException::fromResponse('Flight search API is temporarily unavailable.');
        }

        if (! empty($response['error'])) {
            Log::warning('SerpAPI returned error', [
                'engine' => $query['engine'] ?? null,
                'error' => $response['error'],
            ]);

            throw SerpApiException::fromResponse((string) $response['error']);
        }

        return is_array($response) ? $response : [];
    }

    protected function mapTravelClass(string $cabinClass): string
    {
        $normalized = strtolower(str_replace(' ', '_', trim($cabinClass)));

        return match ($normalized) {
            'business' => '3',
            'first', 'first_class' => '4',
            'premium_economy' => '2',
            default => '1',
        };
    }

    protected function normalizeCabinClass(string $travelClass): string
    {
        return match (strtolower($travelClass)) {
            'premium economy' => 'premium_economy',
            'business' => 'business',
            'first' => 'first',
            default => 'economy',
        };
    }

    protected function formatDuration(int $minutes): string
    {
        if ($minutes <= 0) {
            return '—';
        }

        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;

        if ($hours > 0 && $mins > 0) {
            return "{$hours}h {$mins}m";
        }

        if ($hours > 0) {
            return "{$hours}h";
        }

        return "{$mins}m";
    }

    /**
     * @param  array<string, mixed>  $suggestion
     */
    protected function extractSuggestionCode(array $suggestion): string
    {
        $id = (string) ($suggestion['id'] ?? '');

        if (preg_match('/^[A-Z]{3}$/', $id)) {
            return $id;
        }

        return '';
    }

    protected function extractCountry(string $description): string
    {
        if ($description === '') {
            return '';
        }

        if (str_contains($description, ' in ')) {
            return trim(Str::after($description, ' in '));
        }

        return $description;
    }

    /**
     * @param  array<string, mixed>  $offer
     */
    protected function extractDeepLink(array $offer): ?string
    {
        if (! empty($offer['booking_token'])) {
            return 'https://www.google.com/travel/flights/booking?token='.urlencode((string) $offer['booking_token']);
        }

        return null;
    }
}
