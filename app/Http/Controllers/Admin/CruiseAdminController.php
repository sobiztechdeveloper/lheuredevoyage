<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesCatalog;
use App\Http\Controllers\Controller;
use App\Models\Cruise;

class CruiseAdminController extends Controller
{
    use ManagesCatalog;

    protected function catalogModel(): string
    {
        return Cruise::class;
    }

    protected function catalogLabel(): string
    {
        return 'Cruise';
    }

    protected function catalogMasterDataKey(): ?string
    {
        return 'cruise';
    }

    protected function resourceKey(): string
    {
        return 'cruises';
    }

    protected function catalogUploadDirectory(): string
    {
        return 'cruises';
    }

    protected function catalogFields(): array
    {
        return ['departure_port', 'destination', 'duration'];
    }

    protected function catalogRules(): array
    {
        return [
            'departure_port' => ['required', 'string', 'max:255'],
            'destination' => ['nullable', 'string', 'max:255'],
            'duration' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }
}
