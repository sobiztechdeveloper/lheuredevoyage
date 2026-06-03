<?php

namespace App\Services;

use App\Models\Cruise;
use App\Models\Flight;
use App\Models\Hotel;
use App\Models\RentalCar;
use App\Models\TourPackage;
use App\Models\TravelInsurance;

class SitemapService
{
    /**
     * @return array<int, array{loc: string, lastmod?: string, changefreq?: string, priority?: string}>
     */
    public function urls(): array
    {
        $urls = [
            ['loc' => route('home'), 'changefreq' => 'daily', 'priority' => '1.0'],
            ['loc' => route('about'), 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => route('contact'), 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => route('flight'), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => route('hotel'), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => route('cruise'), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => route('rentalcar'), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => route('travelinsurance'), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => route('tourpackage'), 'changefreq' => 'weekly', 'priority' => '0.8'],
        ];

        foreach (Flight::query()->active()->get(['slug', 'updated_at']) as $item) {
            $urls[] = $this->catalogUrl('flight.show', $item->slug, $item->updated_at);
        }
        foreach (Hotel::query()->active()->get(['slug', 'updated_at']) as $item) {
            $urls[] = $this->catalogUrl('hotel.show', $item->slug, $item->updated_at);
        }
        foreach (Cruise::query()->active()->get(['slug', 'updated_at']) as $item) {
            $urls[] = $this->catalogUrl('cruise.show', $item->slug, $item->updated_at);
        }
        foreach (RentalCar::query()->active()->get(['slug', 'updated_at']) as $item) {
            $urls[] = $this->catalogUrl('rentalcar.show', $item->slug, $item->updated_at);
        }
        foreach (TravelInsurance::query()->active()->get(['slug', 'updated_at']) as $item) {
            $urls[] = $this->catalogUrl('travelinsurance.show', $item->slug, $item->updated_at);
        }
        foreach (TourPackage::query()->active()->get(['slug', 'updated_at']) as $item) {
            $urls[] = $this->catalogUrl('tourpackage.show', $item->slug, $item->updated_at);
        }

        return $urls;
    }

    private function catalogUrl(string $routeName, string $slug, mixed $updatedAt): array
    {
        return [
            'loc' => route($routeName, $slug),
            'lastmod' => $updatedAt?->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.7',
        ];
    }
}
