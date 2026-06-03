<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesCatalog;
use App\Http\Controllers\Controller;
use App\Models\TourPackage;

class TourPackageAdminController extends Controller
{
    use ManagesCatalog;

    protected function catalogModel(): string
    {
        return TourPackage::class;
    }

    protected function catalogLabel(): string
    {
        return 'Tour Package';
    }

    protected function catalogMasterDataKey(): ?string
    {
        return 'tourpackage';
    }

    protected function resourceKey(): string
    {
        return 'packages';
    }

    protected function catalogUploadDirectory(): string
    {
        return 'tour-packages';
    }

    protected function catalogFields(): array
    {
        return ['destination', 'duration'];
    }

    protected function catalogRules(): array
    {
        return [
            'destination' => ['required', 'string', 'max:255'],
            'duration' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }
}
