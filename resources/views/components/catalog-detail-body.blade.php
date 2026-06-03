@props(['item', 'bookableType', 'routePrefix'])

<div class="listing-content">
    @auth
        <form method="POST" action="{{ route('wishlist.toggle') }}" class="mb-3">
            @csrf
            <input type="hidden" name="type" value="{{ $bookableType }}">
            <input type="hidden" name="slug" value="{{ $item->slug }}">
            @php $wishlistKey = class_basename($item).':'.$item->id; @endphp
            <button type="submit" class="btn btn-outline-danger btn-sm">
                <i class="far fa-heart"></i> {{ in_array($wishlistKey, $wishlistedKeys ?? [], true) ? 'Remove from Wishlist' : 'Add to Wishlist' }}
            </button>
        </form>
    @endauth

    <div class="listing-slider owl-carousel owl-theme">
        <img src="{{ $item->image_url }}" alt="{{ $item->title }}">
        @foreach($item->gallery_urls as $galleryUrl)
            <img src="{{ $galleryUrl }}" alt="{{ $item->title }}">
        @endforeach
    </div>
    <div class="listing-header">
        <div class="listing-header-info">
            <h4 class="listing-title">{{ $item->title }}</h4>
            @if($item->location ?? null)
                <p class="listing-location"><i class="far fa-location-dot"></i> {{ $item->location }}</p>
            @elseif($item->destination ?? null)
                <p class="listing-location"><i class="far fa-location-dot"></i> {{ $item->destination }}</p>
            @endif
        </div>
        <div class="listing-rate">
            <span class="badge"><i class="far fa-star"></i> {{ number_format($item->rating, 1) }}</span>
            <span class="listing-rate-type">Excellent</span>
            <span class="listing-rate-review">({{ number_format($item->review_count) }} Reviews)</span>
        </div>
    </div>

    <div class="listing-item">
        <div class="row g-4">
            @if($bookableType === 'hotel')
                <div class="col-md-6 col-lg-3">
                    <div class="listing-feature">
                        <div class="listing-feature-icon"><i class="far fa-hotel"></i></div>
                        <div class="listing-feature-content">
                            <h6>Star Rating</h6>
                            <span>{{ $item->star_rating ?? $item->stars ?? '—' }} Star</span>
                        </div>
                    </div>
                </div>
            @endif
            @if($bookableType === 'tourpackage')
                <div class="col-md-6 col-lg-3">
                    <div class="listing-feature">
                        <div class="listing-feature-icon"><i class="far fa-clock"></i></div>
                        <div class="listing-feature-content">
                            <h6>Duration</h6>
                            <span>{{ $item->duration ?? ($item->duration_days ? $item->duration_days.' Days' : '—') }}</span>
                        </div>
                    </div>
                </div>
            @endif
            @if($bookableType === 'cruise')
                <div class="col-md-6 col-lg-3">
                    <div class="listing-feature">
                        <div class="listing-feature-icon"><i class="far fa-ship"></i></div>
                        <div class="listing-feature-content">
                            <h6>Departure</h6>
                            <span>{{ $item->departure_port ?? '—' }}</span>
                        </div>
                    </div>
                </div>
            @endif
            @if($bookableType === 'rentalcar')
                <div class="col-md-6 col-lg-3">
                    <div class="listing-feature">
                        <div class="listing-feature-icon"><i class="far fa-car"></i></div>
                        <div class="listing-feature-content">
                            <h6>Vehicle</h6>
                            <span>{{ $item->vehicle_type ?? $item->car_type ?? '—' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="listing-feature">
                        <div class="listing-feature-icon"><i class="far fa-users"></i></div>
                        <div class="listing-feature-content">
                            <h6>Capacity</h6>
                            <span>{{ $item->passenger_capacity ?? $item->seats ?? '—' }} seats</span>
                        </div>
                    </div>
                </div>
            @endif
            @if($bookableType === 'travelinsurance')
                <div class="col-md-6 col-lg-3">
                    <div class="listing-feature">
                        <div class="listing-feature-icon"><i class="far fa-shield"></i></div>
                        <div class="listing-feature-content">
                            <h6>Coverage</h6>
                            <span>{{ $item->coverage ?? $item->coverage_type ?? '—' }}</span>
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-md-6 col-lg-3">
                <div class="listing-feature">
                    <div class="listing-feature-icon"><i class="far fa-money-bill"></i></div>
                    <div class="listing-feature-content">
                        <h6>Price</h6>
                        <span>{{ $item->formatted_price }} / {{ $item->price_unit }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($item->short_description)
        <div class="listing-item">
            <h4 class="mb-3">Overview</h4>
            <p>{{ $item->short_description }}</p>
        </div>
    @endif

    @if($item->description)
        <div class="listing-item">
            <h4 class="mb-3">Description</h4>
            <p>{!! nl2br(e($item->description)) !!}</p>
        </div>
    @endif

    @auth
        <div class="listing-item">
            <h4 class="mb-3">Book This {{ ucfirst($bookableType) }}</h4>
            <x-booking-form :item="$item" :bookable-type="$bookableType" />
        </div>
    @else
        <div class="listing-item">
            <a href="{{ route($routePrefix.'.book', $item->slug) }}" class="theme-btn">Book Now <i class="far fa-arrow-right"></i></a>
            <p class="mt-2 text-muted"><a href="{{ route('login') }}">Sign in</a> to book instantly from this page.</p>
        </div>
    @endauth
</div>
