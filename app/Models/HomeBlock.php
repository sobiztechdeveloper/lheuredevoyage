<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class HomeBlock extends Model
{
    protected $fillable = [
        'section', 'title', 'subtitle', 'content', 'image', 'icon', 'link',
        'value', 'sort_order', 'is_active', 'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeSection(Builder $query, string $section): Builder
    {
        return $query->where('section', $section);
    }

    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return '';
        }

        if (str_starts_with($this->image, 'http') || str_starts_with($this->image, 'assets/')) {
            return asset($this->image);
        }

        return asset('storage/'.$this->image);
    }

    public function getIconUrlAttribute(): string
    {
        if (! $this->icon) {
            return '';
        }

        if (str_starts_with($this->icon, 'http') || str_starts_with($this->icon, 'assets/')) {
            return asset($this->icon);
        }

        return asset('storage/'.$this->icon);
    }
}
