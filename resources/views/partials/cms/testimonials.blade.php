@if(isset($testimonials) && $testimonials->isNotEmpty())
<!-- testimonial area -->
<div class="testimonial-area bg pt-70 pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                <div class="site-heading text-center mb-4">
                    <span class="site-title-tagline"><i class="far fa-plane"></i> Testimonials</span>
                    <h2 class="site-title">{{ $sectionTitle ?? 'What Our Customers Say' }}</h2>
                </div>
            </div>
        </div>
        <div class="testimonial-slider owl-carousel owl-theme wow fadeInUp" data-wow-duration="1s" data-wow-delay=".25s">
            @foreach($testimonials as $index => $testimonial)
                <div class="testimonial-single">
                    <div class="testimonial-content">
                        <div class="testimonial-author-img">
                            <img src="{{ $testimonial->image_url }}" alt="{{ $testimonial->name }}">
                        </div>
                    </div>
                    <div class="testimonial-quote">
                        <span class="count">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <div class="testimonial-author-info">
                            <h4>{{ $testimonial->name }}</h4>
                            <p>{{ $testimonial->designation ?? 'Our Client' }}</p>
                        </div>
                        <p>{{ $testimonial->review }}</p>
                        <div class="testimonial-quote-icon">
                            <img src="{{ asset('assets/img/icon/quote.svg') }}" alt="">
                        </div>
                        <div class="testimonial-rate">
                            @for($i = 0; $i < min(5, max(0, (int) $testimonial->rating)); $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- testimonial area end -->
@endif
