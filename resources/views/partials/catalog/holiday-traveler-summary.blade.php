@php
    $parts = [];
    if (($adult ?? 0) > 0) {
        $parts[] = ($adult ?? 0).' '.(($adult ?? 0) === 1 ? 'Adult' : 'Adults');
    }
    if (($children ?? 0) > 0) {
        $parts[] = ($children ?? 0).' '.(($children ?? 0) === 1 ? 'Child' : 'Children');
    }
    if (($infant ?? 0) > 0) {
        $parts[] = ($infant ?? 0).' '.(($infant ?? 0) === 1 ? 'Infant' : 'Infants');
    }
    $summary = $parts !== [] ? implode(', ', $parts) : '2 Adults';
@endphp
{{ $summary }}
