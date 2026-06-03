<?php

namespace App\Models;

use App\Models\Concerns\HasCmsMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use HasCmsMedia, SoftDeletes;

    protected $fillable = [
        'name', 'designation', 'review', 'image', 'rating', 'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'rating' => 'integer',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function getImageUrlAttribute(): string
    {
        return $this->mediaUrl($this->image, 'assets/img/testimonial/01.jpg');
    }
}
