@props(['status'])
@php
    $class = match($status) {
        'new' => 'badge-status-pending',
        'under_review' => 'badge-status-pending',
        'waiting_customer_documents' => 'badge-status-pending',
        'quoted' => 'badge-status-featured',
        'accepted' => 'badge-status-active',
        'rejected' => 'badge-status-inactive',
        'policy_issued' => 'badge-status-active',
        'completed' => 'badge-status-active',
        'cancelled' => 'badge-status-inactive',
        'contacted' => 'badge-status-pending',
        'awaiting_customer' => 'badge-status-pending',
        'confirmed' => 'badge-status-active',
        'policy_sent' => 'badge-status-active',
        default => 'badge-status-pending',
    };
    $label = config('insurance.request_statuses.'.$status, ucfirst(str_replace('_', ' ', $status)));
@endphp
<span class="badge-status {{ $class }}">{{ $label }}</span>
