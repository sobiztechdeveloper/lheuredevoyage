<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
     public function index(): View
    {
        return view('pages.publicView.index', [
            // 'featuredFlights' => Flight::query()
            //     ->orderByDesc('is_trending')
            //     ->orderByDesc('is_popular')
            //     ->orderBy('departure_time')
            //     ->take(3)
            //     ->get(),
            // 'featuredTourPackages' => Tour::query()
            //     ->orderByDesc('is_popular')
            //     ->orderByDesc('is_trending')
            //     ->latest()
            //     ->take(8)
            //     ->get(),
            // 'featuredHotels' => Hotel::query()
            //     ->orderByDesc('is_trending')
            //     ->orderByDesc('is_popular')
            //     ->latest()
            //     ->take(8)
            //     ->get(),
            // 'sliders' => HeroSlider::query()->where('is_active', true)->orderBy('id')->get(),
            // 'homeVideo' => Video::query()->latest()->first(),
            // 'homeBlogs' => Blog::query()->where('is_published', true)->latest()->take(3)->get(),
            // 'homeReviews' => CustomerReview::query()->active()->ordered()->forHomepage()->take(12)->get(),
            // 'stayDestinations' => Location::query()
            //     ->active()
            //     ->withCount('hotels')
            //     ->orderByDesc('hotels_count')
            //     ->orderBy('name')
            //     ->take(8)
            //     ->get(),
        ]);
    }
}
