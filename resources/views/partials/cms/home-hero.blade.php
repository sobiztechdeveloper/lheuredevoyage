@php
    $heroes = $heroSections ?? collect();
    $primary = $heroes->first();
    $bgImage = $primary?->image_url ?? asset('assets/img/hero/hero-2x.jpg');
@endphp
<!-- hero area -->
<div class="hero-section">
    @if($heroes->count() > 1)
        <div class="hero-slider owl-carousel owl-theme">
            @foreach($heroes as $hero)
                <div class="hero-single" style="background: url({{ $hero->image_url }})">
                    <div class="container">
                        @include('partials.cms.hero-content', ['hero' => $hero])
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="hero-single" style="background: url({{ $bgImage }})">
            <div class="container">
                @if($primary)
                    @include('partials.cms.hero-content', ['hero' => $primary])
                @endif
                {{ $slot ?? '' }}
            </div>
        </div>
    @endif
</div>
<!-- hero area end -->
