@php
    $policyName = $request->selected_policy['name'] ?? $request->travelInsurance?->name ?? 'Travel Insurance';
@endphp
<x-mail::message>
@if($type === 'received')
# Insurance booking request received

Thank you for your request for **{{ $policyName }}**.

**Reference:** {{ $request->reference_number }}  
**Travel period:** {{ $request->travel_start->format('M d, Y') }} - {{ $request->travel_end->format('M d, Y') }}  
**Destination:** {{ $request->destination ?: 'Not specified' }}

Our travel consultant will contact you shortly to confirm coverage and final pricing.

@elseif($type === 'admin_new')
# New insurance booking request

**Reference:** {{ $request->reference_number }}  
**Policy:** {{ $policyName }}  
**Email:** {{ $request->contact_email }}  
**Phone:** {{ $request->contact_phone }}

@elseif($type === 'status_changed')
# Status update

Your insurance request **{{ $request->reference_number }}** is now **{{ $request->statusLabel() }}**.

@elseif($type === 'document_uploaded')
# Document available

A **{{ $documentType }}** is now available for request **{{ $request->reference_number }}**.

@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
