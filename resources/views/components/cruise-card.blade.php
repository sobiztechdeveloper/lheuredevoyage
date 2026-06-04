@props(['item', 'searchQuery' => []])

@php
    $wishlistKey = class_basename($item).':'.$item->id;
    $inWishlist = in_array($wishlistKey, $wishlistedKeys ?? [], true);
    $detailUrl = route('cruise.show', $item->slug);
    if (! empty($searchQuery)) {
        $detailUrl .= '?'.http_build_query(array_filter($searchQuery));
    }
    $quoteUrl = route('cruise.quote.wizard', ['cruise' => $item->slug]);
    if (! empty($searchQuery)) {
        $quoteUrl .= '?'.http_build_query(array_filter($searchQuery));
    }
    $nights = $item->duration_nights ?? $item->duration_days;
@endphp

<div class="col-md-6">
    <div class="hotel-item h-100">
        <div class="hotel-img">
            @if($item->is_featured)
                <span class="badge">Featured</span>
            @endif
            @if($item->cruise_region)
                <span class="badge bg-primary" style="left:auto;right:12px;">{{ $item->regionLabel() }}</span>
            @endif
            <img src="{{ $item->image_url }}" alt="{{ $item->displayName() }}">
            @auth
                <form method="POST" action="{{ route('wishlist.toggle') }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="type" value="cruise">
                    <input type="hidden" name="slug" value="{{ $item->slug }}">
                    <button type="submit" class="add-wishlist border-0 bg-transparent {{ $inWishlist ? 'text-danger' : '' }}">
                        <i class="far fa-heart"></i>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="add-wishlist"><i class="far fa-heart"></i></a>
            @endauth
        </div>
        <div class="hotel-content">
            @if($item->cruise_line)
                <p class="small text-muted mb-1"><i class="far fa-ship me-1"></i>{{ $item->cruise_line }}@if($item->ship_name) · {{ $item->ship_name }}@endif</p>
            @endif
            <h4 class="hotel-title">
                <a href="{{ $detailUrl }}">{{ $item->displayName() }}</a>
            </h4>
            @if($item->departure_port || $item->arrival_port)
                <p class="mb-1"><i class="far fa-location-dot"></i> {{ $item->departure_port }}@if($item->arrival_port) → {{ $item->arrival_port }}@endif</p>
            @elseif($item->location)
                <p class="mb-1"><i class="far fa-location-dot"></i> {{ $item->location }}</p>
            @endif
            @if($nights)
                <p class="small text-muted mb-2">{{ $nights }} {{ Str::plural('night', (int) $nights) }}</p>
            @endif
            @if($item->short_description)
                <p class="small text-muted mb-2">{{ Str::limit($item->short_description, 90) }}</p>
            @endif
            <div class="hotel-rate">
                <span class="badge"><i class="far fa-star"></i> {{ number_format((float) $item->rating, 1) }}</span>
                <span class="hotel-rate-review">({{ number_format($item->review_count) }} reviews)</span>
            </div>
            <div class="hotel-bottom flex-wrap gap-2">
                <div class="hotel-price">
                    <span class="hotel-price-amount">{{ $item->startingPriceDisplay() }}
                        <span class="hotel-price-type">/ person</span>
                    </span>
                </div>
                <div class="hotel-text-btn d-flex flex-wrap gap-2">
                    <a href="{{ $detailUrl }}">Details <i class="fas fa-arrow-right"></i></a>
                    <a href="{{ $quoteUrl }}" class="text-primary">Request Quote</a>
                </div>
            </div>
        </div>
    </div>
</div>
