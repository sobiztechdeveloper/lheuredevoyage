<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class InsuranceRequestDocument extends Model
{
    protected $fillable = [
        'insurance_booking_request_id', 'document_type', 'disk', 'path',
        'original_name', 'uploaded_by',
    ];

    public function bookingRequest(): BelongsTo
    {
        return $this->belongsTo(InsuranceBookingRequest::class, 'insurance_booking_request_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function label(): string
    {
        $types = array_merge(
            config('insurance.customer_document_types', []),
            config('insurance.admin_document_types', [])
        );

        return $types[$this->document_type] ?? ucfirst(str_replace('_', ' ', $this->document_type));
    }

    public function existsOnDisk(): bool
    {
        return Storage::disk($this->disk)->exists($this->path);
    }
}
