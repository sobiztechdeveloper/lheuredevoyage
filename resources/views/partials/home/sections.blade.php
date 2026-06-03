<!-- partner area -->
@if(($partners ?? collect())->isNotEmpty())
<div class="partner-area2 bg pt-40 pb-40">
    <div class="container">
        <div class="partner-slider owl-carousel owl-theme">
            @foreach($partners as $partner)
                <img src="{{ $partner->image_url }}" alt="{{ $partner->title ?? 'Partner' }}">
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- feature area -->
@if(($features ?? collect())->isNotEmpty())
<div class="feature-area2 pt-100">
    <div class="container">
        <div class="feature-wrapper">
            <div class="row g-4">
                @foreach($features as $feature)
                    <div class="col-lg-6 col-xl-4">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <img src="{{ $feature->icon_url }}" alt="">
                            </div>
                            <div class="feature-content">
                                <h4 class="feature-title">{{ $feature->title }}</h4>
                                <p>{{ $feature->content }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

<!-- about-area -->
@if($about)
<div class="about-area py-120">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="about-left wow fadeInLeft" data-wow-delay=".25s">
                    <div class="about-img">
                        <div class="row">
                            <div class="col-6">
                                <img class="img-1" src="{{ $about->image_primary ? asset($about->image_primary) : asset('assets/img/about/01.jpg') }}" alt="">
                            </div>
                            <div class="col-6">
                                <img class="img-2" src="{{ $about->image_secondary ? asset($about->image_secondary) : asset('assets/img/about/02.jpg') }}" alt="">
                            </div>
                        </div>
                    </div>
                    @if($about->experience_years)
                    <div class="about-experience">
                        <h5>{{ $about->experience_years }}<span>+</span></h5>
                        <p>Years Of Experience</p>
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-right wow fadeInUp" data-wow-delay=".25s">
                    <div class="site-heading mb-3">
                        <span class="site-title-tagline"><i class="far fa-plane"></i> {{ $about->subheading ?? 'About Us' }}</span>
                        <h2 class="site-title">{!! $about->heading ?? "We Are The World <span>Best Travel Booking</span> Agency" !!}</h2>
                    </div>
                    <p class="about-text">{{ $about->content }}</p>
                    <a href="{{ route('about') }}" class="theme-btn">Discover More <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- counter area -->
@if(($counters ?? collect())->isNotEmpty())
<div class="counter-area">
    <div class="col-lg-11 col-xl-9">
        <div class="counter-wrap">
            <div class="row">
                @foreach($counters as $counter)
                    @php $meta = $counter->metadata ?? []; @endphp
                    <div class="col-lg-3 col-sm-6">
                        <div class="counter-box">
                            <div class="icon">
                                <img src="{{ $counter->icon_url }}" alt="">
                            </div>
                            <div class="counter-content">
                                <div class="counter-number">
                                    <span class="counter" data-count="{{ $meta['prefix'] ?? '+' }}" data-to="{{ $counter->display_value ?? $counter->value }}" data-speed="3000">{{ $counter->display_value ?? $counter->value }}</span>
                                    <span class="counter-sign">{{ $meta['suffix'] ?? '' }}</span>
                                </div>
                                <h6 class="title">{{ $counter->title }}</h6>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

<!-- cta-area -->
@if($cta)
<div class="cta-area">
    <div class="container">
        <div class="cta-wrapper">
            <div class="col-md-10 col-lg-8 col-xl-6 mx-auto">
                <div class="cta-content">
                    <div class="cta-text">
                        <h1>{!! $cta->title !!}</h1>
                        <p>{{ $cta->content }}</p>
                    </div>
                    <a href="{{ $cta->link ? url($cta->link) : route('contact') }}" class="theme-btn mt-20">Book Now <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @if($cta->image_url)
            <div class="cta-img">
                <img src="{{ $cta->image_url }}" alt="">
            </div>
            @endif
        </div>
    </div>
</div>
@endif

<!-- choose area -->
@if(($chooseItems ?? collect())->isNotEmpty())
<div class="choose-area py-120">
    <div class="container">
        @if($chooseHeader)
        <div class="row">
            <div class="col-lg-6 mx-auto wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                <div class="site-heading text-center">
                    <span class="site-title-tagline"><i class="far fa-plane"></i> {{ $chooseHeader->subtitle }}</span>
                    <h2 class="site-title">{{ $chooseHeader->title }}</h2>
                </div>
            </div>
        </div>
        @endif
        <div class="row align-items-center">
            <div class="col-lg-6 wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".25s">
                @foreach($chooseItems as $item)
                    <div class="choose-item">
                        <span class="count">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <div class="icon">
                            <img src="{{ $item->icon_url }}" alt="">
                        </div>
                        <div class="content">
                            <h4>{{ $item->title }}</h4>
                            <p>{{ $item->content }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-lg-6 wow fadeInRight" data-wow-duration="1s" data-wow-delay=".25s">
                <div class="choose-img">
                    @php $meta = $chooseHeader?->metadata ?? []; @endphp
                    @if(!empty($meta['shape']))
                        <img class="shape" src="{{ asset($meta['shape']) }}" alt="">
                    @endif
                    <img class="img-1" src="{{ $chooseHeader?->image_url ?: asset('assets/img/choose/01.jpg') }}" alt="">
                    @if(!empty($meta['image_secondary']))
                        <img class="img-2" src="{{ asset($meta['image_secondary']) }}" alt="">
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif
