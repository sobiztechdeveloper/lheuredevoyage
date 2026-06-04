<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CruiseBookingRequest extends Model
{
    use SoftDeletes;

    public const STATUSES = [
        'new',
        'under_review',
        'waiting_documents',
        'quoted',
        'accepted',
        'rejected',
        'voucher_sent',
        'completed',
        'cancelled',
    ];

    protected $fillable = [
        'reference_number', 'customer_id', 'cruise_id', 'cruise_cabin_id', 'status',
        'departure_date', 'return_date', 'cabin_type', 'adults', 'children', 'infants',
        'contact_title', 'contact_name', 'contact_email', 'contact_phone', 'contact_whatsapp',
        'country', 'address', 'city',
        'dining_preference', 'bed_preference', 'celebration',
        'dietary_requirements', 'wheelchair_assistance', 'mobility_assistance', 'special_needs',
        'medical_conditions', 'additional_notes', 'special_requests',
        'emergency_contact_name', 'emergency_contact_relationship', 'emergency_contact_phone', 'emergency_contact_email',
        'selected_cruise', 'estimated_amount', 'currency', 'agent_notes',
        'voucher_path', 'invoice_path', 'ticket_path', 'boarding_instructions_path', 'excursion_details_path',
        'privacy_accepted', 'terms_accepted_at',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'departure_date' => 'date',
            'return_date' => 'date',
            'wheelchair_assistance' => 'boolean',
            'mobility_assistance' => 'boolean',
            'special_needs' => 'boolean',
            'privacy_accepted' => 'boolean',
            'terms_accepted_at' => 'datetime',
            'selected_cruise' => 'array',
            'estimated_amount' => 'decimal:2',
        ];
    }

    public static function generateReference(): string
    {
        $date = now()->format('Ymd');

        do {
            $suffix = str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
            $reference = "CR-{$date}-{$suffix}";
        } while (static::query()->where('reference_number', $reference)->exists());

        return $reference;
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function cruise(): BelongsTo
    {
        return $this->belongsTo(Cruise::class);
    }

    public function cabin(): BelongsTo
    {
        return $this->belongsTo(CruiseCabin::class, 'cruise_cabin_id');
    }

    public function passengers(): HasMany
    {
        return $this->hasMany(CruiseBookingPassenger::class)->orderByDesc('is_primary')->orderBy('id');
    }

    public function primaryPassenger(): ?CruiseBookingPassenger
    {
        return $this->passengers->firstWhere('is_primary', true) ?? $this->passengers->first();
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(CruiseBookingRequestStatusHistory::class)->orderBy('created_at');
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(CruiseRequestDocument::class);
    }

    public function statusLabel(): string
    {
        return config('cruise.request_statuses.'.$this->status, ucfirst(str_replace('_', ' ', $this->status)));
    }

    public function passengerCount(): int
    {
        $count = $this->passengers()->count();

        return $count > 0 ? $count : ((int) $this->adults + (int) $this->children + (int) $this->infants);
    }
}
