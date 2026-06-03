@if(!empty($hero->title) || !empty($hero->subtitle))
    <div class="hero-content text-center text-white mb-4 py-3">
        @if($hero->title)<h1 class="hero-title">{{ $hero->title }}</h1>@endif
        @if($hero->subtitle)<p class="mb-3">{{ $hero->subtitle }}</p>@endif
        @if($hero->button_text && $hero->button_url)
            <a href="{{ $hero->button_url }}" class="theme-btn">{{ $hero->button_text }} <i class="far fa-arrow-right"></i></a>
        @endif
    </div>
@endif
