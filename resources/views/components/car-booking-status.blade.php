@props(['status'])
@php
    $class = match($status) {
        'new' => 'badge-status-pending',
        'contacted' => 'badge-status-pending',
        'quoted' => 'badge-status-featured',
        'awaiting_customer' => 'badge-status-pending',
        'confirmed' => 'badge-status-active',
        'voucher_sent' => 'badge-status-active',
        'completed' => 'badge-status-active',
        'cancelled' => 'badge-status-inactive',
        default => 'badge-status-pending',
    };
@endphp
<span class="badge-status {{ $class }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
