@props([
    'title' => 'Booking Request',
    'breadcrumbs' => [],
    'steps' => [],
    'formAction' => '#',
    'formMethod' => 'POST',
    'formEnctype' => null,
    'formId' => 'fbw-form',
    'notice' => null,
])

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/flight-booking-wizard.css') }}">
@isset($styles){{ $styles }}@endisset
@endpush

<div class="site-breadcrumb" style="background: url({{ asset('assets/img/breadcrumb/01.jpg') }})">
    <div class="container">
        <h2 class="breadcrumb-title">{{ $title }}</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            @foreach($breadcrumbs as $crumb)
                @if(!empty($crumb['url']))
                    <li><a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a></li>
                @else
                    <li class="active">{{ $crumb['label'] }}</li>
                @endif
            @endforeach
        </ul>
    </div>
</div>

<div class="py-100 fbw-main">
    <div class="container">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($notice)
            <div class="fbw-notice mb-4">{{ $notice }}</div>
        @endif

        @if(count($steps) > 0)
        <ul class="fbw-progress" aria-label="Booking steps">
            @foreach($steps as $index => $stepLabel)
                <li data-step="{{ $index + 1 }}" @class(['active' => $index === 0])>
                    <span class="fbw-step-dot">{{ $index + 1 }}</span>
                    <span class="fbw-step-label">{{ $stepLabel }}</span>
                </li>
            @endforeach
        </ul>
        @endif

        <form id="{{ $formId }}" action="{{ $formAction }}" method="{{ $formMethod }}"
            @if($formEnctype) enctype="{{ $formEnctype }}" @endif novalidate>
            @isset($hiddenFields){!! $hiddenFields !!}@endisset
            <div class="row g-4">
                <div class="{{ isset($sidebar) ? 'col-lg-8' : 'col-12' }}">
                    {{ $slot }}
                </div>
                @isset($sidebar)
                <div class="col-lg-4">
                    {{ $sidebar }}
                </div>
                @endisset
            </div>
        </form>
    </div>
</div>
