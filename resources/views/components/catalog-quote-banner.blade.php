@props([
    'type',
    'searchQuery' => [],
])

@php
    $config = \App\Support\CatalogEmptyState::config($type);
@endphp

@if($config)
    <div {{ $attributes->class(['catalog-quote-banner']) }}>
        <h4 class="text-white mb-2">{{ $config['banner_title'] }}</h4>
        <p class="mb-3 opacity-75">{{ $config['banner_message'] }}</p>
        <a href="{{ \App\Support\CatalogEmptyState::quoteUrl($type, $searchQuery) }}" class="theme-btn catalog-quote-banner-btn">{{ $config['banner_cta'] }}</a>
    </div>
@endif

@once
    @push('styles')
        <style>
            .catalog-empty-state {
                background: #f4f7fc;
                text-align: center;
                padding: 3rem 1.5rem;
                border-radius: 12px;
            }

            .catalog-quote-banner {
                background: linear-gradient(135deg, #162F65, #3361AC);
                color: #fff;
                text-align: center;
                padding: 2rem 1.5rem;
                border-radius: 12px;
                margin-top: 1.5rem;
            }

            .catalog-quote-banner-btn {
                background: #F8B501 !important;
                border-color: #F8B501 !important;
                color: #162F65 !important;
            }

            .catalog-empty-state .fbw-btn-outline {
                display: inline-block;
                padding: 0.65rem 1.5rem;
                border-radius: 50px;
                border: 2px solid #162F65;
                color: #162F65;
                font-weight: 600;
                text-decoration: none;
                transition: background 0.2s, color 0.2s;
            }

            .catalog-empty-state .fbw-btn-outline:hover {
                background: #162F65;
                color: #fff;
            }
        </style>
    @endpush
@endonce
