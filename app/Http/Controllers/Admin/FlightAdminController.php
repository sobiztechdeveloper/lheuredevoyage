<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesCatalog;
use App\Http\Controllers\Controller;
use App\Models\Flight;

class FlightAdminController extends Controller
{
    use ManagesCatalog;

    protected function catalogModel(): string
    {
        return Flight::class;
    }

    protected function catalogLabel(): string
    {
        return 'Flight';
    }

    protected function resourceKey(): string
    {
        return 'flights';
    }

    protected function catalogUploadDirectory(): string
    {
        return 'flights';
    }

    protected function catalogFields(): array
    {
        return [
            'airline', 'flight_number', 'departure_city', 'arrival_city', 'location',
            'duration', 'flight_class', 'stops', 'refundable_type', 'baggage_kg',
            'departure_time', 'arrival_time',
        ];
    }

    protected function catalogRules(): array
    {
        return [
            'airline' => ['nullable', 'string', 'max:255'],
            'flight_number' => ['nullable', 'string', 'max:20'],
            'departure_city' => ['nullable', 'string', 'max:255'],
            'arrival_city' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'duration' => ['nullable', 'string', 'max:50'],
            'flight_class' => ['nullable', 'string', 'max:50'],
            'stops' => ['nullable', 'integer', 'min:0'],
            'refundable_type' => ['nullable', 'string', 'max:30'],
            'baggage_kg' => ['nullable', 'integer', 'min:0', 'max:100'],
            'departure_time' => ['nullable', 'date_format:H:i'],
            'arrival_time' => ['nullable', 'date_format:H:i'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }
}
