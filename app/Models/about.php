<?php

namespace App\Models;

use App\Models\Concerns\HasCmsMedia;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasCmsMedia;

    protected $fillable = [
        'heading', 'subheading', 'content', 'image_primary', 'image_secondary',
        'breadcrumb_image', 'experience_years', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function getBreadcrumbImageUrlAttribute(): string
    {
        return $this->mediaUrl(
            $this->breadcrumb_image,
            config('page-banners.pages.about', config('page-banners.default'))
        );
    }
}
