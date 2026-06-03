@props([
    'title' => null,
    'description' => null,
    'keywords' => null,
    'canonical' => null,
    'ogImage' => null,
])
@php
    $siteName = ($siteSettings ?? null)?->company_name ?? "L'Heure De Voyage";
    $pageTitle = $title ?? $siteName;
    $metaDescription = $description ?? "Book flights, hotels, cruises, rental cars, travel insurance and holiday packages with L'Heure De Voyage.";
    $metaKeywords = $keywords ?? 'travel, flights, hotels, cruises, holiday packages, L Heure De Voyage';
    $canonicalUrl = $canonical ?? url()->current();
    $image = $ogImage ?? ($siteSettings ?? null)?->logo_url ?? asset('assets/img/logo/logo.png');
@endphp
<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
<meta name="keywords" content="{{ $metaKeywords }}">
<link rel="canonical" href="{{ $canonicalUrl }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image">
