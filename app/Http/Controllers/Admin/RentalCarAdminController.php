<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesCatalog;
use App\Http\Controllers\Controller;
use App\Models\RentalCar;

class RentalCarAdminController extends Controller
{
    use ManagesCatalog;

    protected function catalogModel(): string
    {
        return RentalCar::class;
    }

    protected function catalogLabel(): string
    {
        return 'Rental Car';
    }

    protected function catalogMasterDataKey(): ?string
    {
        return 'rentalcar';
    }

    protected function resourceKey(): string
    {
        return 'cars';
    }

    protected function catalogUploadDirectory(): string
    {
        return 'rental-cars';
    }

    protected function catalogFields(): array
    {
        return ['vehicle_type', 'passenger_capacity', 'price_per_day'];
    }

    protected function catalogRules(): array
    {
        return [
            'vehicle_type' => ['required', 'string', 'max:100'],
            'passenger_capacity' => ['nullable', 'integer', 'min:1', 'max:20'],
            'price_per_day' => ['required', 'numeric', 'min:0'],
        ];
    }
}
