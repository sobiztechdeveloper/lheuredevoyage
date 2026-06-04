@props(['item', 'searchQuery' => []])

@php
    $wishlistKey = class_basename($item).':'.$item->id;
    $inWishlist = in_array($wishlistKey, $wishlistedKeys ?? [], true);
    $detailUrl = route('travelinsurance.show', $item->slug);
    if (! empty($searchQuery)) {
        $detailUrl .= '?'.http_build_query(array_filter($searchQuery));
    }
    $quoteUrl = route('travelinsurance.quote.wizard', ['travelInsurance' => $item->slug]);
    if (! empty($searchQuery)) {
        $quoteUrl .= '?'.http_build_query(array_filter($searchQuery));
    }
@endphp

<div class="col-md-6">
    <div class="hotel-item insurance-plan-item h-100">
        <div class="hotel-img">
            @if($item->is_featured)
                <span class="badge">Featured</span>
            @endif
            @if($item->plan_type)
                <span class="badge bg-primary" style="left:auto;right:12px;">{{ $item->planTypeLabel() }}</span>
            @endif
            <img src="{{ $item->featuredImageUrl() }}" alt="{{ $item->displayPlanName() }}">
            @auth
                <form method="POST" action="{{ route('wishlist.toggle') }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="type" value="travelinsurance">
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
            <div class="d-flex align-items-center gap-2 mb-2">
                @if($item->logo)
                    <img src="{{ $item->logoUrl() }}" alt="" height="28" class="rounded">
                @endif
                <span class="small text-muted mb-0">{{ $item->displayCompany() }}</span>
            </div>
            <h4 class="hotel-title">
                <a href="{{ $detailUrl }}">{{ $item->displayPlanName() }}</a>
            </h4>
            @if($item->short_description)
                <p class="small text-muted mb-2">{{ Str::limit($item->short_description, 100) }}</p>
            @endif
            @if($item->formattedMedicalCoverage())
                <p class="mb-1"><i class="far fa-shield-check text-primary me-1"></i><strong>Medical:</strong> {{ $item->formattedMedicalCoverage() }}</p>
            @endif
            @if($item->relationLoaded('benefits') && $item->benefits->isNotEmpty())
                <ul class="small ps-3 mb-2">
                    @foreach($item->benefits->take(3) as $benefit)
                        <li>{{ $benefit->title }}</li>
                    @endforeach
                </ul>
            @endif
            <div class="d-flex flex-wrap gap-1 mb-2">
                @if($item->schengen_covered)<span class="badge bg-light text-dark border">Schengen</span>@endif
                @if($item->worldwide_covered)<span class="badge bg-light text-dark border">Worldwide</span>@endif
                @if($item->covid_coverage)<span class="badge bg-light text-dark border">COVID</span>@endif
            </div>
            <div class="hotel-rate">
                <span class="badge"><i class="far fa-star"></i> {{ number_format((float) $item->rating, 1) }}</span>
                <span class="hotel-rate-review">({{ number_format($item->review_count) }} reviews)</span>
            </div>
            <div class="hotel-bottom flex-wrap gap-2">
                <div class="hotel-price">
                    <span class="hotel-price-amount">{{ $item->displayPremium() }}
                        <span class="hotel-price-type">/ {{ $item->price_per_person ? 'person' : ($item->price_per_family ? 'family' : 'plan') }}</span>
                    </span>
                    <span class="d-block small text-muted">Indicative premium</span>
                </div>
                <div class="d-flex flex-column gap-1 align-items-end">
                    <a href="{{ $quoteUrl }}" class="theme-btn btn-sm">Request Quote</a>
                    <a href="{{ $detailUrl }}" class="hotel-text-btn">View plan <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
