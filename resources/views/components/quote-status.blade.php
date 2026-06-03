@props(['status'])
@php
    $labels = [
        'draft' => 'Draft',
        'sent' => 'Sent',
        'viewed' => 'Viewed',
        'accepted' => 'Accepted',
        'rejected' => 'Rejected',
        'expired' => 'Expired',
    ];
    $classes = [
        'draft' => 'qt-status-draft',
        'sent' => 'qt-status-sent',
        'viewed' => 'qt-status-viewed',
        'accepted' => 'qt-status-accepted',
        'rejected' => 'qt-status-rejected',
        'expired' => 'qt-status-expired',
    ];
@endphp
<span class="qt-status {{ $classes[$status] ?? 'qt-status-draft' }}">{{ $labels[$status] ?? ucfirst($status) }}</span>
