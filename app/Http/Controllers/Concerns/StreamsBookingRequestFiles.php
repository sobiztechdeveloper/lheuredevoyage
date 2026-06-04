<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait StreamsBookingRequestFiles
{
    protected function streamBookingRequestFile(?string $path, ?string $preferredDisk = null): StreamedResponse
    {
        if (! $path) {
            abort(404);
        }

        $disks = array_filter([
            $preferredDisk,
            'local',
            'public',
        ]);

        foreach (array_unique($disks) as $disk) {
            if (Storage::disk($disk)->exists($path)) {
                return Storage::disk($disk)->response($path);
            }
        }

        abort(404);
    }
}
