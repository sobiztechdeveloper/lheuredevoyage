<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CmsImageUploader
{
    public function upload(?UploadedFile $file, string $directory, ?string $existingPath = null): ?string
    {
        if (! $file) {
            return $existingPath;
        }

        if ($existingPath && ! str_starts_with($existingPath, 'http')) {
            Storage::disk('public')->delete($existingPath);
        }

        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();

        return $file->storeAs('cms/'.$directory, $filename, 'public');
    }

    public function url(?string $path, ?string $fallback = null): string
    {
        if (! $path) {
            return $fallback ? asset($fallback) : '';
        }

        if (str_starts_with($path, 'http') || str_starts_with($path, 'assets/')) {
            return asset($path);
        }

        return Storage::disk('public')->url($path);
    }
}
