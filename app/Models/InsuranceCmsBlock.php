<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class InsuranceCmsBlock extends Model
{
    protected $fillable = ['block_key', 'title', 'content', 'is_active', 'updated_by'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('insurance_cms_blocks'));
        static::deleted(fn () => Cache::forget('insurance_cms_blocks'));
    }

    public static function cachedActive(): \Illuminate\Support\Collection
    {
        return Cache::remember('insurance_cms_blocks', 3600, fn () => static::query()
            ->where('is_active', true)
            ->orderBy('block_key')
            ->get()
            ->keyBy('block_key'));
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
