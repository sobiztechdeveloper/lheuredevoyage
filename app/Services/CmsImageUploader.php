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
            return $fallback ? $this->publicUrl($fallback) : '';
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, 'assets/')) {
            return $this->publicUrl($path);
        }

        return $this->publicUrl('storage/'.ltrim(str_replace('\\', '/', $path), '/'));
    }

    /**
     * Build a URL using the current request host (works on 127.0.0.1:8000 and production).
     */
    protected function publicUrl(string $relativePath): string
    {
        $path = '/'.ltrim(str_replace('\\', '/', $relativePath), '/');

        if (! app()->runningInConsole() && request()->getHost()) {
            return request()->getSchemeAndHttpHost().$path;
        }

        return asset(ltrim($path, '/'));
    }
}
