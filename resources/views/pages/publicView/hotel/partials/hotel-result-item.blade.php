@php
    $detailUrl = route('hotel.show', $result->slug);
    if (isset($search)) {
        $detailUrl .= '?'.http_build_query($search->bookingQueryParams());
    }
    $wishlistKey = 'Hotel:'.$result->hotel_id;
    $inWishlist = in_array($wishlistKey, $wishlistedKeys ?? [], true);
@endphp
<div class="col-md-6 col-lg-4">
    <div class="hotel-item">
        <div class="hotel-img">
            @if($result->is_featured)
                <span class="badge">Featured</span>
            @endif
            <img src="{{ $result->image_url }}" alt="{{ $result->title }}">
            @auth
                <form method="POST" action="{{ route('wishlist.toggle') }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="type" value="hotel">
                    <input type="hidden" name="slug" value="{{ $result->slug }}">
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
                <a href="{{ $detailUrl }}">{{ $result->title }}</a>
            </h4>
            @if($result->location)
                <p><i class="far fa-location-dot"></i> {{ $result->location }}</p>
            @endif
            <div class="hotel-rate">
                <span class="badge"><i class="far fa-star"></i> {{ number_format((float) $result->rating, 1) }}</span>
                <span class="hotel-rate-type">Excellent</span>
                <span class="hotel-rate-review">({{ number_format($result->review_count) }} Reviews)</span>
            </div>
            <div class="hotel-bottom">
                <div class="hotel-price">
                    <span class="hotel-price-amount">{{ $result->formatted_price }}
                        <span class="hotel-price-type">/Per Person</span>
                    </span>
                </div>
                <div class="hotel-text-btn">
                    <a href="{{ $detailUrl }}">See Details <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
