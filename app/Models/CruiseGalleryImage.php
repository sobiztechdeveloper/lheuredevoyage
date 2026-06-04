<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CruiseGalleryImage extends Model
{
    protected $fillable = ['cruise_id', 'path', 'sort_order'];

    public function cruise(): BelongsTo
    {
        return $this->belongsTo(Cruise::class);
    }
}
