@props(['item', 'routePrefix', 'bookableType' => null])

@php
    $type = $bookableType ?? $routePrefix;
    $wishlistKey = class_basename($item).':'.$item->id;
    $inWishlist = in_array($wishlistKey, $wishlistedKeys ?? [], true);
@endphp

<div class="col-md-6 col-lg-4">
    <div class="hotel-item">
        <div class="hotel-img">
            @if($item->is_featured)
                <span class="badge">Featured</span>
            @endif
            <img src="{{ $item->image_url }}" alt="{{ $item->title }}">
            @auth
                <form method="POST" action="{{ route('wishlist.toggle') }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <input type="hidden" name="slug" value="{{ $item->slug }}">
                    <button type="submit" class="add-wishlist border-0 bg-transparent {{ $inWishlist ? 'text-danger' : '' }}" title="{{ $inWishlist ? 'Remove from wishlist' : 'Add to wishlist' }}">
                        <i class="far fa-heart"></i>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="add-wishlist" title="Sign in to save"><i class="far fa-heart"></i></a>
            @endauth
        </div>
        <div class="hotel-content">
            <h4 class="hotel-title">
                <a href="{{ route($routePrefix.'.show', $item->slug) }}">{{ $item->title }}</a>
            </h4>
            @if($item->location ?? null)
                <p><i class="far fa-location-dot"></i> {{ $item->location }}</p>
            @elseif($item->destination ?? null)
                <p><i class="far fa-location-dot"></i> {{ $item->destination }}</p>
            @endif
            <div class="hotel-rate">
                <span class="badge"><i class="far fa-star"></i> {{ number_format($item->rating, 1) }}</span>
                <span class="hotel-rate-type">Excellent</span>
                <span class="hotel-rate-review">({{ number_format($item->review_count) }} Reviews)</span>
            </div>
            <div class="hotel-bottom">
                <div class="hotel-price">
                    <span class="hotel-price-amount">{{ $item->formatted_price }}
                        <span class="hotel-price-type">/{{ $item->price_unit }}</span>
                    </span>
                </div>
                <div class="hotel-text-btn">
                    <a href="{{ route($routePrefix.'.show', $item->slug) }}">See Details <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
