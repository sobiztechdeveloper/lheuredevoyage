@php
    $catalogSections = [
        ['items' => $featuredHotels ?? collect(), 'title' => 'Featured Hotels', 'tagline' => 'Hotels', 'route' => 'hotel', 'class' => 'hotel-area'],
        ['items' => $latestHotels ?? collect(), 'title' => 'Latest Hotels', 'tagline' => 'New Arrivals', 'route' => 'hotel', 'class' => 'hotel-area bg'],
        ['items' => $popularHotels ?? collect(), 'title' => 'Popular Hotels', 'tagline' => 'Top Rated', 'route' => 'hotel', 'class' => 'hotel-area'],
        ['items' => $featuredTourPackages ?? collect(), 'title' => 'Featured Tour Packages', 'tagline' => 'Packages', 'route' => 'tourpackage', 'class' => 'tour-area'],
        ['items' => $latestTourPackages ?? collect(), 'title' => 'Latest Packages', 'tagline' => 'Packages', 'route' => 'tourpackage', 'class' => 'tour-area bg'],
        ['items' => $popularTourPackages ?? collect(), 'title' => 'Popular Packages', 'tagline' => 'Top Rated', 'route' => 'tourpackage', 'class' => 'tour-area'],
        ['items' => $featuredCruises ?? collect(), 'title' => 'Featured Cruises', 'tagline' => 'Cruises', 'route' => 'cruise', 'class' => 'cruise-area'],
        ['items' => $latestCruises ?? collect(), 'title' => 'Latest Cruises', 'tagline' => 'Cruises', 'route' => 'cruise', 'class' => 'cruise-area bg'],
        ['items' => $featuredCars ?? collect(), 'title' => 'Featured Rental Cars', 'tagline' => 'Cars', 'route' => 'rentalcar', 'class' => 'car-area'],
        ['items' => $latestCars ?? collect(), 'title' => 'Latest Rental Cars', 'tagline' => 'Cars', 'route' => 'rentalcar', 'class' => 'car-area bg'],
        ['items' => $featuredInsurances ?? collect(), 'title' => 'Travel Insurance Plans', 'tagline' => 'Insurance', 'route' => 'travelinsurance', 'class' => 'insurance-area'],
    ];
@endphp

@foreach($catalogSections as $section)
    @if($section['items']->isNotEmpty())
    <section class="{{ $section['class'] }} py-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <div class="site-heading text-center">
                        <span class="site-title-tagline">{{ $section['tagline'] }}</span>
                        <h2 class="site-title">{{ $section['title'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                @foreach($section['items'] as $item)
                    <x-catalog-card :item="$item" :routePrefix="$section['route']" />
                @endforeach
            </div>
            <div class="text-center mt-4">
                <a href="{{ route($section['route']) }}" class="theme-btn">View All <i class="far fa-arrow-right"></i></a>
            </div>
        </div>
    </section>
    @endif
@endforeach
