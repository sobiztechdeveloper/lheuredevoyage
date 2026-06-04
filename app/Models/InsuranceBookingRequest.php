<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class InsuranceBookingRequest extends Model
{
    use SoftDeletes;

    public const STATUSES = [
        'new',
        'under_review',
        'waiting_customer_documents',
        'quoted',
        'accepted',
        'rejected',
        'policy_issued',
        'completed',
        'cancelled',
    ];

    protected $fillable = [
        'reference_number', 'customer_id', 'travel_insurance_id', 'status',
        'destination', 'destination_country', 'purpose_of_travel',
        'travel_start', 'travel_end', 'coverage_type',
        'pre_existing_conditions', 'pregnancy', 'adventure_sports', 'winter_sports', 'long_stay',
        'medical_notes', 'additional_notes',
        'contact_email', 'contact_phone', 'contact_whatsapp', 'address', 'country', 'city',
        'selected_policy', 'estimated_amount', 'currency', 'agent_notes',
        'policy_path', 'invoice_path', 'coverage_document_path', 'claim_instructions_path',
        'privacy_accepted', 'terms_accepted_at',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'travel_start' => 'date',
            'travel_end' => 'date',
            'pre_existing_conditions' => 'boolean',
            'pregnancy' => 'boolean',
            'adventure_sports' => 'boolean',
            'winter_sports' => 'boolean',
            'long_stay' => 'boolean',
            'privacy_accepted' => 'boolean',
            'terms_accepted_at' => 'datetime',
            'selected_policy' => 'array',
            'estimated_amount' => 'decimal:2',
        ];
    }

    public static function generateReference(): string
    {
        $date = now()->format('Ymd');

        do {
            $suffix = str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
            $reference = "INS-{$date}-{$suffix}";
        } while (static::query()->where('reference_number', $reference)->exists());

        return $reference;
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function travelInsurance(): BelongsTo
    {
        return $this->belongsTo(TravelInsurance::class);
    }

    public function travelers(): HasMany
    {
        return $this->hasMany(InsuranceBookingTraveler::class)->orderByDesc('is_primary')->orderBy('id');
    }

    public function primaryTraveler(): ?InsuranceBookingTraveler
    {
        return $this->travelers->firstWhere('is_primary', true) ?? $this->travelers->first();
    }

    public function additionalTravelers()
    {
        return $this->travelers->where('is_primary', false);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(InsuranceBookingRequestStatusHistory::class)->orderBy('created_at');
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(InsuranceRequestDocument::class);
    }

    public function statusLabel(): string
    {
        return config('insurance.request_statuses.'.$this->status, Str::title(str_replace('_', ' ', $this->status)));
    }

    public function purposeLabel(): string
    {
        return config('insurance.travel_purposes.'.$this->purpose_of_travel, $this->purpose_of_travel ?? '—');
    }

    public function documentRoute(string $documentType): ?string
    {
        if ($this->policy_path && $documentType === 'policy') {
            return route('booking-files.insurance.document', [$this, 'policy']);
        }
        if ($this->invoice_path && $documentType === 'invoice') {
            return route('booking-files.insurance.document', [$this, 'invoice']);
        }
        if ($this->coverage_document_path && $documentType === 'coverage') {
            return route('booking-files.insurance.document', [$this, 'coverage']);
        }
        if ($this->claim_instructions_path && $documentType === 'claim_instructions') {
            return route('booking-files.insurance.document', [$this, 'claim_instructions']);
        }

        $doc = $this->documents->firstWhere('document_type', $documentType);

        return $doc
            ? route('booking-files.insurance.uploaded', [$this, $doc])
            : null;
    }
}
