@props(['item', 'routePrefix'])

<div class="alert alert-info mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3 class="mb-1">{{ $item->title }}</h3>
            @if($item->location)
                <p class="mb-0"><i class="far fa-location-dot"></i> {{ $item->location }}</p>
            @endif
            <p class="mb-0 mt-2"><strong>{{ $item->formatted_price }}</strong> / {{ $item->price_unit }}</p>
        </div>
        <a href="{{ \App\Support\CatalogUrls::bookUrl($routePrefix, $item) }}" class="theme-btn">Book Now <i class="far fa-arrow-right"></i></a>
    </div>
</div>
