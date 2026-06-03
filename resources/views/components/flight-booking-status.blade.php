@props(['status'])
@php
    $labels = [
        'new' => 'New',
        'contacted' => 'Contacted',
        'quoted' => 'Quoted',
        'awaiting_customer' => 'Awaiting Customer',
        'confirmed' => 'Confirmed',
        'ticketed' => 'Ticketed',
        'cancelled' => 'Cancelled',
    ];
    $classes = [
        'new' => 'fb-status-new',
        'contacted' => 'fb-status-contacted',
        'quoted' => 'fb-status-quoted',
        'awaiting_customer' => 'fb-status-awaiting',
        'confirmed' => 'fb-status-confirmed',
        'ticketed' => 'fb-status-ticketed',
        'cancelled' => 'fb-status-cancelled',
    ];
@endphp
<span class="fb-status {{ $classes[$status] ?? 'fb-status-new' }}">{{ $labels[$status] ?? ucfirst($status) }}</span>
