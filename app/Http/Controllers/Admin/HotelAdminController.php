<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesCatalog;
use App\Http\Controllers\Controller;
use App\Models\Hotel;

class HotelAdminController extends Controller
{
    use ManagesCatalog;

    protected function catalogModel(): string
    {
        return Hotel::class;
    }

    protected function catalogLabel(): string
    {
        return 'Hotel';
    }

    protected function catalogMasterDataKey(): ?string
    {
        return 'hotel';
    }

    protected function resourceKey(): string
    {
        return 'hotels';
    }

    protected function catalogUploadDirectory(): string
    {
        return 'hotels';
    }

    protected function catalogFields(): array
    {
        return ['star_rating', 'location'];
    }

    protected function catalogRules(): array
    {
        return [
            'location' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'star_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'stars' => ['nullable', 'integer', 'min:1', 'max:5'],
        ];
    }
}
