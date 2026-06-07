@props([
    'type',
    'hasSearch' => true,
    'searchQuery' => [],
])

@php
    $config = \App\Support\CatalogEmptyState::config($type);
@endphp

@if($config)
    <div {{ $attributes->class(['col-12']) }}>
        <div class="catalog-empty-state">
            <i class="far {{ $config['icon'] }} fa-3x text-primary mb-3"></i>
            <h5>
                {{ $hasSearch ? $config['empty_title'] : ($config['no_search_title'] ?? $config['empty_title']) }}
            </h5>
            <p class="text-muted mb-3">
                {{ $hasSearch ? $config['empty_message'] : ($config['no_search_message'] ?? $config['empty_message']) }}
            </p>
            @if($hasSearch)
                <a href="{{ route($config['index_route']) }}" class="theme-btn me-2">{{ $config['view_all_label'] }}</a>
                <a href="{{ \App\Support\CatalogEmptyState::quoteUrl($type, $searchQuery) }}" class="fbw-btn-outline d-inline-block px-4 py-2 rounded">Request Quote</a>
            @endif
        </div>
    </div>
@endif
