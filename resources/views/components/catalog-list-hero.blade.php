@props([
    'title',
    'page',
    'searchAreaClass' => 'search-common',
])

@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/catalog-list-hero.css') }}?v={{ file_exists(public_path('assets/css/catalog-list-hero.css')) ? filemtime(public_path('assets/css/catalog-list-hero.css')) : 1 }}">
    @endpush
@endonce

<div {{ $attributes->class([
    'catalog-list-hero',
    'catalog-list-hero--flight' => $searchAreaClass === 'flight-search',
]) }}>
    <x-site-breadcrumb :title="$title" :page="$page">
        @isset($breadcrumb)
            {{ $breadcrumb }}
        @endisset
    </x-site-breadcrumb>

    <div class="search-area {{ $searchAreaClass }} catalog-list-search">
        <div class="container">
            {{ $search }}
        </div>
    </div>
</div>
