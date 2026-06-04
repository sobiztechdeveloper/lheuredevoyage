<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class HotelBookingRequestStatusHistory extends Model
{
    protected $fillable = [
        'hotel_booking_request_id', 'old_status', 'new_status', 'notes', 'changed_by',
    ];

    public function bookingRequest(): BelongsTo
    {
        return $this->belongsTo(HotelBookingRequest::class, 'hotel_booking_request_id');
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
