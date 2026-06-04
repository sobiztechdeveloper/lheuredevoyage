<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CarBookingRequest extends Model
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

    protected $fillable = [
        'reference_number', 'customer_id', 'rental_car_id', 'status',
        'pickup_location', 'dropoff_location', 'pickup_date', 'pickup_time', 'return_date', 'return_time',
        'contact_email', 'contact_phone', 'contact_whatsapp', 'address', 'country',
        'extra_gps', 'extra_child_seat', 'extra_additional_driver', 'insurance_option', 'notes',
        'selected_vehicle', 'estimated_amount', 'currency', 'agent_notes',
        'voucher_path', 'invoice_path', 'rental_agreement_path',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'pickup_date' => 'date',
            'return_date' => 'date',
            'extra_gps' => 'boolean',
            'extra_child_seat' => 'boolean',
            'extra_additional_driver' => 'boolean',
            'selected_vehicle' => 'array',
            'estimated_amount' => 'decimal:2',
        ];
    }

    public static function generateReference(): string
    {
        $date = now()->format('Ymd');

        do {
            $suffix = str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
            $reference = "CAR-{$date}-{$suffix}";
        } while (static::query()->where('reference_number', $reference)->exists());

        return $reference;
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function rentalCar(): BelongsTo
    {
        return $this->belongsTo(RentalCar::class);
    }

    public function drivers(): HasMany
    {
        return $this->hasMany(CarBookingDriver::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(CarBookingRequestStatusHistory::class)->orderBy('created_at');
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function statusLabel(): string
    {
        return Str::title(str_replace('_', ' ', $this->status));
    }

    public function voucherFileUrl(): ?string
    {
        return $this->voucher_path ? asset('storage/'.$this->voucher_path) : null;
    }

    public function invoiceFileUrl(): ?string
    {
        return $this->invoice_path ? asset('storage/'.$this->invoice_path) : null;
    }

    public function rentalAgreementFileUrl(): ?string
    {
        return $this->rental_agreement_path ? asset('storage/'.$this->rental_agreement_path) : null;
    }
}
