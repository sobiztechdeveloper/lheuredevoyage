<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Booking;
use App\Models\Cruise;
use App\Models\Faq;
use App\Models\HeroSection;
use App\Models\HomeBlock;
use App\Models\Hotel;
use App\Models\RentalCar;
use App\Models\Testimonial;
use App\Models\TourPackage;
use App\Models\TravelInsurance;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $blocks = HomeBlock::query()->active()->orderBy('sort_order')->get()->groupBy('section');

        $counters = $this->hydrateCounters($blocks->get('counter', collect()));

        return view('pages.publicView.index', [
            'heroSections' => HeroSection::query()->active()->ordered()->get(),
            'partners' => $blocks->get('partner', collect()),
            'features' => $blocks->get('feature', collect()),
            'about' => About::query()->where('is_active', true)->first(),
            'counters' => $counters,
            'cta' => $blocks->get('cta', collect())->first(),
            'chooseHeader' => $blocks->get('choose_header', collect())->first(),
            'chooseItems' => $blocks->get('choose', collect()),
            'featuredHotels' => Hotel::query()->active()->featured()->latest()->take(6)->get(),
            'latestHotels' => Hotel::query()->active()->latest()->take(6)->get(),
            'popularHotels' => Hotel::query()->active()->orderByDesc('review_count')->take(6)->get(),
            'featuredTourPackages' => TourPackage::query()->active()->featured()->latest()->take(6)->get(),
            'latestTourPackages' => TourPackage::query()->active()->latest()->take(6)->get(),
            'popularTourPackages' => TourPackage::query()->active()->orderByDesc('review_count')->take(6)->get(),
            'featuredCruises' => Cruise::query()->active()->featured()->latest()->take(3)->get(),
            'latestCruises' => Cruise::query()->active()->latest()->take(3)->get(),
            'featuredCars' => RentalCar::query()->active()->featured()->latest()->take(4)->get(),
            'latestCars' => RentalCar::query()->active()->latest()->take(4)->get(),
            'featuredInsurances' => TravelInsurance::query()->active()->featured()->latest()->take(4)->get(),
            'testimonials' => Testimonial::query()->active()->latest()->take(8)->get(),
            'faqs' => Faq::query()->active()->ordered()->take(6)->get(),
        ]);
    }

    private function hydrateCounters($counters)
    {
        $stats = [
            'Booking Done' => $this->formatStat(Booking::query()->count()),
            'Our Destination' => $this->formatStat(
                TourPackage::query()->active()->distinct('destination')->count('destination')
                + Hotel::query()->active()->distinct('location')->count('location')
            ),
            'Happy Clients' => $this->formatStat(User::query()->count()),
            'Our Partners' => (string) HomeBlock::query()->active()->section('partner')->count(),
        ];

        return $counters->map(function (HomeBlock $block) use ($stats) {
            if (isset($stats[$block->title])) {
                $block->display_value = $stats[$block->title];
            } else {
                $block->display_value = $block->value;
            }

            return $block;
        });
    }

    private function formatStat(int $value): string
    {
        if ($value >= 1000) {
            return (string) round($value / 1000);
        }

        return (string) $value;
    }
}
