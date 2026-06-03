<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class FlightBookingRequest extends Model
{
    public const STATUSES = [
        'new',
        'contacted',
        'quoted',
        'awaiting_customer',
        'confirmed',
        'ticketed',
        'cancelled',
    ];

    public const SPECIAL_ASSISTANCE_OPTIONS = [
        'wheelchair' => 'Wheelchair Assistance',
        'medical_assistance' => 'Medical Assistance',
        'traveling_with_infant' => 'Traveling With Infant',
        'senior_citizen_assistance' => 'Senior Citizen Assistance',
    ];

    public const SEAT_PREFERENCES = [
        'no_preference' => 'No Preference',
        'window' => 'Window',
        'aisle' => 'Aisle',
        'middle' => 'Middle',
    ];

    public const MEAL_PREFERENCES = [
        'no_preference' => 'No Preference',
        'vegetarian' => 'Vegetarian',
        'vegan' => 'Vegan',
        'halal' => 'Halal',
        'kosher' => 'Kosher',
        'child_meal' => 'Child Meal',
    ];

    protected $fillable = [
        'booking_reference', 'user_id', 'flight_search_id', 'flight_search_result_id',
        'trip_type', 'origin', 'destination', 'departure_date', 'return_date',
        'adults', 'children', 'infants', 'cabin_class', 'selected_flight',
        'contact_name', 'contact_passenger_index', 'email', 'phone', 'whatsapp', 'country',
        'billing_address', 'billing_city', 'billing_country', 'postal_code',
        'special_assistance', 'special_requests', 'preferred_airline', 'seat_preference', 'meal_preference',
        'estimated_price', 'currency',
        'status', 'agent_notes', 'ticket_path', 'invoice_path',
    ];

    protected function casts(): array
    {
        return [
            'departure_date' => 'date',
            'return_date' => 'date',
            'selected_flight' => 'array',
            'special_assistance' => 'array',
            'estimated_price' => 'decimal:2',
        ];
    }

    public static function generateReference(): string
    {
        $date = now()->format('Ymd');
        $suffix = str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);

        do {
            $reference = "BK-{$date}-{$suffix}";
            $suffix = str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (static::query()->where('booking_reference', $reference)->exists());

        return $reference;
    }

    public function flightSearch(): BelongsTo
    {
        return $this->belongsTo(FlightSearch::class);
    }

    public function flightSearchResult(): BelongsTo
    {
        return $this->belongsTo(FlightSearchResult::class);
    }

    public function passengers(): HasMany
    {
        return $this->hasMany(FlightBookingPassenger::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(FlightBookingRequestStatusHistory::class)->orderBy('created_at');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function statusLabel(): string
    {
        return Str::title(str_replace('_', ' ', $this->status));
    }

    public function passengerCount(): int
    {
        return $this->passengers()->count() ?: ((int) $this->adults + (int) $this->children + (int) $this->infants);
    }

    public function routeLabel(): string
    {
        return $this->origin.' → '.$this->destination;
    }

    public function seatPreferenceLabel(): string
    {
        return self::SEAT_PREFERENCES[$this->seat_preference] ?? 'No Preference';
    }

    public function mealPreferenceLabel(): string
    {
        return self::MEAL_PREFERENCES[$this->meal_preference] ?? 'No Preference';
    }

    public function ticketFileUrl(): ?string
    {
        return $this->ticket_path ? asset('storage/'.$this->ticket_path) : null;
    }

    public function invoiceFileUrl(): ?string
    {
        return $this->invoice_path ? asset('storage/'.$this->invoice_path) : null;
    }
}
