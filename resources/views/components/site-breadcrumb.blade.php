@props([
    'title',
    'page' => null,
    'image' => null,
    'homeLabel' => 'Home',
])

@php
    $backgroundUrl = $image ?? \App\Support\PageBanner::breadcrumbUrl($page);
@endphp

<div {{ $attributes->merge(['class' => 'site-breadcrumb']) }} style="background-image: url({{ e($backgroundUrl) }});">
    <div class="container">
        <h2 class="breadcrumb-title">{{ $title }}</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ route('home') }}">{{ $homeLabel }}</a></li>
            @if(isset($slot) && ! $slot->isEmpty())
                {{ $slot }}
            @else
                <li class="active">{{ $title }}</li>
            @endif
        </ul>
    </div>
</div>
