<?php

namespace App\Models\Concerns;

use App\Services\CmsImageUploader;

trait HasCmsMedia
{
    public function mediaUrl(?string $path, ?string $fallback = null): string
    {
        return app(CmsImageUploader::class)->url($path, $fallback);
    }
}
