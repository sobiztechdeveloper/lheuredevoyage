<?php

namespace Database\Seeders;

use App\Models\HomeBlock;
use Illuminate\Database\Seeder;

class HomeBlockSeeder extends Seeder
{
    public function run(): void
    {
        $partners = ['01.png', '02.png', '03.png', '04.png'];
        foreach ($partners as $i => $file) {
            HomeBlock::query()->updateOrCreate(
                ['section' => 'partner', 'sort_order' => $i + 1],
                ['image' => 'assets/img/partner/'.$file, 'is_active' => true]
            );
        }

        $features = [
            ['title' => 'Worldwide Coverage', 'content' => 'Explore destinations across every continent with curated travel experiences.', 'icon' => 'assets/img/icon/world.svg'],
            ['title' => 'Best Quality Services', 'content' => 'Premium partners, verified listings, and transparent pricing on every booking.', 'icon' => 'assets/img/icon/quality.svg'],
            ['title' => '24/7 Customer Service', 'content' => 'Our travel experts are available around the clock to assist your journey.', 'icon' => 'assets/img/icon/support.svg'],
        ];
        foreach ($features as $i => $feature) {
            HomeBlock::query()->updateOrCreate(
                ['section' => 'feature', 'sort_order' => $i + 1],
                array_merge($feature, ['is_active' => true])
            );
        }

        HomeBlock::query()->updateOrCreate(
            ['section' => 'cta', 'sort_order' => 1],
            [
                'title' => 'First Booking Get 70% Discount!',
                'content' => 'Book your next adventure with L\'Heure De Voyage and enjoy exclusive member offers.',
                'image' => 'assets/img/cta/01.jpg',
                'link' => '/contact',
                'is_active' => true,
            ]
        );

        $choose = [
            ['title' => 'Safety And Trust', 'content' => 'Verified partners and secure booking flows protect every trip you plan.', 'icon' => 'assets/img/icon/safety.svg'],
            ['title' => '100% Price Transparency', 'content' => 'No hidden fees — see full pricing before you confirm your reservation.', 'icon' => 'assets/img/icon/price.svg'],
            ['title' => 'Travel With More Confidence', 'content' => 'Flexible policies and dedicated support from search to departure.', 'icon' => 'assets/img/icon/booking-confirm.svg'],
        ];
        foreach ($choose as $i => $item) {
            HomeBlock::query()->updateOrCreate(
                ['section' => 'choose', 'sort_order' => $i + 1],
                array_merge($item, ['is_active' => true])
            );
        }

        HomeBlock::query()->updateOrCreate(
            ['section' => 'choose_header', 'sort_order' => 1],
            [
                'subtitle' => 'Why Choose Us',
                'title' => 'Discover Beautiful Place With Us',
                'image' => 'assets/img/choose/01.jpg',
                'metadata' => ['image_secondary' => 'assets/img/choose/02.jpg', 'shape' => 'assets/img/shape/04.png'],
                'is_active' => true,
            ]
        );

        $counters = [
            ['title' => 'Booking Done', 'value' => '120', 'icon' => 'assets/img/icon/booking-confirm.svg', 'metadata' => ['suffix' => 'k', 'prefix' => '+']],
            ['title' => 'Our Destination', 'value' => '200', 'icon' => 'assets/img/icon/destination.svg', 'metadata' => ['suffix' => '+', 'prefix' => '+']],
            ['title' => 'Happy Clients', 'value' => '40', 'icon' => 'assets/img/icon/rating.svg', 'metadata' => ['suffix' => 'k', 'prefix' => '+']],
            ['title' => 'Our Partners', 'value' => '180', 'icon' => 'assets/img/icon/partner.svg', 'metadata' => ['suffix' => '+', 'prefix' => '+']],
        ];
        foreach ($counters as $i => $counter) {
            HomeBlock::query()->updateOrCreate(
                ['section' => 'counter', 'sort_order' => $i + 1],
                array_merge($counter, ['is_active' => true])
            );
        }
    }
}
