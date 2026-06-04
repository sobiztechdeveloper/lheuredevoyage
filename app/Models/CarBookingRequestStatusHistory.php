<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class CarBookingRequestStatusHistory extends Model
{
    protected $fillable = [
        'car_booking_request_id', 'old_status', 'new_status', 'notes', 'changed_by',
    ];

    public function bookingRequest(): BelongsTo
    {
        return $this->belongsTo(CarBookingRequest::class, 'car_booking_request_id');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function newStatusLabel(): string
    {
        return Str::title(str_replace('_', ' ', $this->new_status));
    }
}
