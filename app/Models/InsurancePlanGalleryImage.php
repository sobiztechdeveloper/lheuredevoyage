<?php

namespace App\Models;

use App\Models\Concerns\HasCmsMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsurancePlanGalleryImage extends Model
{
    use HasCmsMedia;

    protected $fillable = ['travel_insurance_id', 'path', 'sort_order'];

    public function travelInsurance(): BelongsTo
    {
        return $this->belongsTo(TravelInsurance::class);
    }

    public function getImageUrlAttribute(): string
    {
        return $this->mediaUrl($this->path);
    }
}
