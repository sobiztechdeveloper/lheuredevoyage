@props(['item', 'routePrefix' => 'tourpackage', 'searchQuery' => []])

@php
    $detailUrl = route($routePrefix.'.show', $item->slug);
    if (! empty($searchQuery)) {
        $detailUrl .= '?'.http_build_query($searchQuery);
    }
    $included = $item->includedServiceLabels();
@endphp

<div class="col-md-6 col-lg-4">
    <div class="hotel-item">
        <div class="hotel-img">
            @if($item->is_featured)
                <span class="badge">Featured</span>
            @endif
            <img src="{{ $item->image_url }}" alt="{{ $item->title }}">
        </div>
        <div class="hotel-content">
            <h4 class="hotel-title">
                <a href="{{ $detailUrl }}">{{ $item->title }}</a>
            </h4>
            @if($item->displayCountry())
                <p><i class="far fa-location-dot"></i> {{ $item->displayCountry() }}</p>
            @endif
            @if($item->displayDuration())
                <p><i class="far fa-clock"></i> {{ $item->displayDuration() }}</p>
            @endif
            @if($item->holidayTypeLabel())
                <p><i class="far fa-umbrella-beach"></i> {{ $item->holidayTypeLabel() }}</p>
            @endif
            @if($item->short_description)
                <p class="mb-2">{{ \Illuminate\Support\Str::limit($item->short_description, 110) }}</p>
            @endif
            @if($included !== [])
                <div class="mb-2">
                    <strong>Included:</strong>
                    <ul class="list-unstyled mb-0 mt-1">
                        @foreach($included as $service)
                            <li><i class="far fa-check text-success"></i> {{ $service }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="hotel-bottom">
                <div class="hotel-price">
                    <span class="hotel-price-amount">{{ $item->formatted_price }}
                        <span class="hotel-price-type">/{{ $item->price_unit }}</span>
                    </span>
                </div>
                <div class="hotel-text-btn">
                    <a href="{{ $detailUrl }}">View Package <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
