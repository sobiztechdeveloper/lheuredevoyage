<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CatalogImageUploader
{
    public function uploadFeatured(?UploadedFile $file, string $directory, ?string $existingPath = null): ?string
    {
        return $this->store($file, 'catalog/'.$directory.'/featured', $existingPath);
    }

    /**
     * @param  array<int, UploadedFile>  $files
     * @return array<int, string>
     */
    public function uploadGallery(array $files, string $directory, array $existing = []): array
    {
        $paths = $existing;

        foreach ($files as $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $stored = $this->store($file, 'catalog/'.$directory.'/gallery');

            if ($stored) {
                $paths[] = $stored;
            }
        }

        return array_values($paths);
    }

    private function store(?UploadedFile $file, string $directory, ?string $existingPath = null): ?string
    {
        if (! $file) {
            return $existingPath;
        }

        if ($existingPath && ! str_starts_with($existingPath, 'http') && ! str_starts_with($existingPath, 'assets/')) {
            Storage::disk('public')->delete($existingPath);
        }

        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();

        return $file->storeAs($directory, $filename, 'public');
    }
}
