<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class HotelBookingRequest extends Model
{
    use SoftDeletes;

    public const STATUSES = [
        'new',
        'contacted',
        'quoted',
        'awaiting_customer',
        'confirmed',
        'voucher_sent',
        'completed',
        'cancelled',
    ];

    public const BED_PREFERENCES = [
        'no_preference' => 'No Preference',
        'king' => 'King Bed',
        'queen' => 'Queen Bed',
        'twin' => 'Twin Beds',
        'double' => 'Double Bed',
    ];

    public const SMOKING_PREFERENCES = [
        'no_preference' => 'No Preference',
        'smoking' => 'Smoking',
        'non_smoking' => 'Non-Smoking',
    ];

    public const SPECIAL_REQUEST_OPTIONS = [
        'airport_transfer' => 'Airport Transfer',
        'early_check_in' => 'Early Check-In',
        'late_check_out' => 'Late Check-Out',
        'extra_bed' => 'Extra Bed',
        'honeymoon_decoration' => 'Honeymoon Decoration',
        'birthday_decoration' => 'Birthday Decoration',
        'anniversary_setup' => 'Anniversary Setup',
        'special_meal' => 'Special Meal',
    ];

    public const ARRIVAL_TIMES = [
        'morning' => 'Morning (06:00 - 12:00)',
        'afternoon' => 'Afternoon (12:00 - 18:00)',
        'evening' => 'Evening (18:00 - 22:00)',
        'late_night' => 'Late Night (22:00+)',
        'unknown' => 'Not Sure',
    ];

    protected $fillable = [
        'reference_number', 'customer_id', 'hotel_id', 'room_id', 'status',
        'check_in_date', 'check_out_date', 'rooms', 'adults', 'children', 'infants',
        'lead_guest_title', 'lead_guest_name', 'lead_guest_email', 'lead_guest_phone',
        'lead_guest_whatsapp', 'country', 'bed_preference', 'smoking_preference', 'arrival_time',
        'special_request_options', 'special_requests', 'selected_hotel', 'selected_room',
        'estimated_amount', 'currency', 'agent_notes',
        'voucher_path', 'invoice_path', 'transfer_voucher_path',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'check_in_date' => 'date',
            'check_out_date' => 'date',
            'special_request_options' => 'array',
            'selected_hotel' => 'array',
            'selected_room' => 'array',
            'estimated_amount' => 'decimal:2',
        ];
    }

    public static function generateReference(): string
    {
        $date = now()->format('Ymd');

        do {
            $suffix = str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
            $reference = "HT-{$date}-{$suffix}";
        } while (static::query()->where('reference_number', $reference)->exists());

        return $reference;
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(HotelRoom::class, 'room_id');
    }

    public function guests(): HasMany
    {
        return $this->hasMany(HotelBookingGuest::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(HotelBookingRequestStatusHistory::class)->orderBy('created_at');
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function statusLabel(): string
    {
        return Str::title(str_replace('_', ' ', $this->status));
    }

    public function nights(): int
    {
        return max(1, (int) $this->check_in_date->diffInDays($this->check_out_date));
    }

    public function guestCount(): int
    {
        $count = $this->guests()->count();

        return $count > 0 ? $count : ((int) $this->adults + (int) $this->children + (int) $this->infants);
    }

    public function bedPreferenceLabel(): string
    {
        return self::BED_PREFERENCES[$this->bed_preference] ?? 'No Preference';
    }

    public function smokingPreferenceLabel(): string
    {
        return self::SMOKING_PREFERENCES[$this->smoking_preference] ?? 'No Preference';
    }

    public function arrivalTimeLabel(): string
    {
        return self::ARRIVAL_TIMES[$this->arrival_time] ?? ($this->arrival_time ?: '—');
    }

    public function voucherFileUrl(): ?string
    {
        return $this->voucher_path ? asset('storage/'.$this->voucher_path) : null;
    }

    public function invoiceFileUrl(): ?string
    {
        return $this->invoice_path ? asset('storage/'.$this->invoice_path) : null;
    }

    public function transferVoucherFileUrl(): ?string
    {
        return $this->transfer_voucher_path ? asset('storage/'.$this->transfer_voucher_path) : null;
    }
}
