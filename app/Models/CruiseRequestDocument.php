<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CruiseRequestDocument extends Model
{
    protected $fillable = [
        'cruise_booking_request_id', 'document_type', 'disk', 'path',
        'original_name', 'uploaded_by',
    ];

    public function bookingRequest(): BelongsTo
    {
        return $this->belongsTo(CruiseBookingRequest::class, 'cruise_booking_request_id');
    }
}
