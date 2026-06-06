<?php

namespace Database\Seeders;

use App\Models\CruiseLine;
use Illuminate\Database\Seeder;

class CruiseLineSeeder extends Seeder
{
    public function run(): void
    {
        $lines = [
            'MSC Cruises',
            'Royal Caribbean',
            'Costa Cruises',
            'Norwegian Cruise Line',
            'Celebrity Cruises',
        ];

        foreach ($lines as $index => $name) {
            CruiseLine::query()->updateOrCreate(
                ['slug' => CruiseLine::generateSlug($name)],
                [
                    'name' => $name,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ],
            );
        }
    }
}
