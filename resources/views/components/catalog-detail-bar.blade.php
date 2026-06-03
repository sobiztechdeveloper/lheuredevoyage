@props(['item', 'routePrefix', 'label'])

<div class="site-breadcrumb" style="background: url({{ asset('assets/img/breadcrumb/05.jpg') }})">
    <div class="container">
        <h2 class="breadcrumb-title">{{ $item->title }}</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route($routePrefix) }}">{{ $label }}</a></li>
            <li class="active">{{ $item->title }}</li>
        </ul>
    </div>
</div>
