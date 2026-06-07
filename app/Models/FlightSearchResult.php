<?php

namespace App\Models;

use App\Services\AirlineLogoResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlightSearchResult extends Model
{
    protected $fillable = [
        'flight_search_id', 'flight_id', 'external_offer_id', 'airline', 'flight_number', 'from_destination', 'to_destination',
        'departure_at', 'arrival_at', 'duration', 'stops', 'cabin_class', 'price', 'currency',
        'refundable_type', 'baggage_kg', 'raw_offer',
    ];

    protected function casts(): array
    {
        return [
            'departure_at' => 'datetime',
            'arrival_at' => 'datetime',
            'price' => 'decimal:2',
            'raw_offer' => 'array',
        ];
    }

    public function flightSearch(): BelongsTo
    {
        return $this->belongsTo(FlightSearch::class);
    }

    public function catalogFlight(): BelongsTo
    {
        return $this->belongsTo(Flight::class, 'flight_id');
    }

    public function formattedDisplayPrice(int $decimals = 0): string
    {
        $source = strtoupper((string) ($this->currency ?: currency_service()->serpapiSource()));

        return format_money((float) $this->price, $source, $decimals);
    }

    public function airlineLogoUrl(): ?string
    {
        if ($this->catalogFlight?->image_url) {
            return $this->catalogFlight->image_url;
        }

        if (is_array($this->raw_offer)) {
            $serpLogo = trim((string) ($this->raw_offer['airline_logo'] ?? ''));

            if ($serpLogo !== '') {
                return $serpLogo;
            }
        }

        return app(AirlineLogoResolver::class)->resolve(
            (string) $this->airline,
            (string) $this->flight_number
        );
    }

    public function stopLabel(): string
    {
        return match ((int) $this->stops) {
            0 => 'Non Stop',
            1 => '1 Stop',
            2 => '2 Stop',
            default => $this->stops.' Stop',
        };
    }

    public function refundableLabel(): string
    {
        return match ($this->refundable_type) {
            'refundable' => 'Refundable',
            'non_refundable' => 'Non Refundable',
            default => 'As Per Rules',
        };
    }

    public function destinationCode(string $destination): string
    {
        $clean = preg_replace('/[^A-Za-z]/', '', $destination) ?? '';

        return strtoupper(substr($clean, 0, 3)) ?: strtoupper(substr($destination, 0, 3));
    }

    public function cabinClassLabel(): string
    {
        return match ($this->cabin_class) {
            'premium_economy' => 'Premium Economy',
            'business' => 'Business',
            'first' => 'First Class',
            default => 'Economy',
        };
    }

    public function airlineInitials(): string
    {
        $words = preg_split('/\s+/', trim((string) $this->airline)) ?: [];

        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1).substr($words[1], 0, 1));
        }

        return strtoupper(substr((string) $this->airline, 0, 2));
    }

    public static function destinationCodeFrom(?string $destination): string
    {
        if ($destination === null || $destination === '') {
            return '';
        }

        $clean = preg_replace('/[^A-Za-z]/', '', $destination) ?? '';

        return strtoupper(substr($clean, 0, 3)) ?: strtoupper(substr($destination, 0, 3));
    }

    public function bookingDeepLink(): ?string
    {
        if (! is_array($this->raw_offer)) {
            return null;
        }

        if (! empty($this->raw_offer['deep_link'])) {
            return (string) $this->raw_offer['deep_link'];
        }

        if (! empty($this->raw_offer['booking_token'])) {
            return 'https://www.google.com/travel/flights/booking?token='.urlencode((string) $this->raw_offer['booking_token']);
        }

        return null;
    }

    /**
     * @return list<array{name: string, code: string, duration: string}>
     */
    public function layoverDetails(): array
    {
        if (! is_array($this->raw_offer)) {
            return [];
        }

        $details = [];

        foreach ($this->raw_offer['layovers'] ?? [] as $layover) {
            $name = trim((string) ($layover['name'] ?? ''));
            $code = trim((string) ($layover['id'] ?? ''));

            if ($name === '' && $code === '') {
                continue;
            }

            $details[] = [
                'name' => $name !== '' ? $name : $code,
                'code' => $code,
                'duration' => $this->formatLayoverDuration((int) ($layover['duration'] ?? 0)),
            ];
        }

        return $details;
    }

    public function aircraftLabel(): ?string
    {
        if (! is_array($this->raw_offer)) {
            return null;
        }

        $types = [];

        foreach ($this->raw_offer['flights'] ?? [] as $segment) {
            $airplane = trim((string) ($segment['airplane'] ?? ''));

            if ($airplane !== '') {
                $types[$airplane] = $airplane;
            }
        }

        if ($types === []) {
            return null;
        }

        return implode(', ', array_values($types));
    }

    /**
     * @return list<string>
     */
    public function amenities(): array
    {
        if (! is_array($this->raw_offer)) {
            return [];
        }

        $amenities = [];

        foreach ($this->raw_offer['flights'] ?? [] as $segment) {
            foreach ($segment['extensions'] ?? [] as $extension) {
                $label = trim((string) $extension);

                if ($label === '' || stripos($label, 'carbon emissions') !== false) {
                    continue;
                }

                $amenities[$label] = $label;
            }
        }

        return array_values($amenities);
    }

    public function carbonEmissionsLabel(): ?string
    {
        if (! is_array($this->raw_offer)) {
            return null;
        }

        $carbon = $this->raw_offer['carbon_emissions'] ?? null;

        if (! is_array($carbon) || empty($carbon['this_flight'])) {
            return null;
        }

        $kg = (int) round(((int) $carbon['this_flight']) / 1000);
        $label = number_format($kg).' kg CO₂';

        if (isset($carbon['difference_percent']) && $carbon['difference_percent'] !== null && $carbon['difference_percent'] !== '') {
            $diff = (int) $carbon['difference_percent'];
            $label .= $diff < 0
                ? ' ('.abs($diff).'% below typical)'
                : ($diff > 0 ? ' ('.$diff.'% above typical)' : ' (typical for route)');
        }

        return $label;
    }

    public function segmentFlightNumbers(): string
    {
        if (! is_array($this->raw_offer)) {
            return (string) $this->flight_number;
        }

        $numbers = [];

        foreach ($this->raw_offer['flights'] ?? [] as $segment) {
            $number = trim((string) ($segment['flight_number'] ?? ''));

            if ($number !== '') {
                $numbers[$number] = $number;
            }
        }

        if ($numbers === []) {
            return (string) $this->flight_number;
        }

        return implode(', ', array_values($numbers));
    }

    private function formatLayoverDuration(int $minutes): string
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
}
