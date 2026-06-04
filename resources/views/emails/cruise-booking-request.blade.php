@php
    $cruiseName = $request->selected_cruise['name'] ?? $request->cruise?->name ?? 'Cruise';
@endphp
<x-mail::message>
@if($type === 'received')
# Cruise booking request received

Thank you for your request at **{{ $cruiseName }}**.

**Reference:** {{ $request->reference_number }}  
**Departure:** {{ $request->departure_date->format('M d, Y') }}  
@if($request->return_date)**Return:** {{ $request->return_date->format('M d, Y') }}@endif

Our travel consultant will contact you shortly to confirm availability and final pricing.

@elseif($type === 'admin_new')
# New cruise booking request

**Reference:** {{ $request->reference_number }}  
**Cruise:** {{ $cruiseName }}  
**Contact:** {{ $request->contact_name }}  
**Email:** {{ $request->contact_email }}

@elseif($type === 'status_changed')
# Status update

Your cruise request **{{ $request->reference_number }}** is now **{{ $request->statusLabel() }}**.

@elseif($type === 'document_uploaded')
# Document available

A **{{ $documentType }}** is now available for request **{{ $request->reference_number }}**.

@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
