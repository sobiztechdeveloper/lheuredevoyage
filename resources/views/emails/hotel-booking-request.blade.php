@php
    $hotelName = $request->selected_hotel['name'] ?? $request->hotel?->name ?? 'Hotel';
@endphp
<x-mail::message>
@if($type === 'received')
# Hotel booking request received

Thank you for your request at **{{ $hotelName }}**.

**Reference:** {{ $request->reference_number }}  
**Check-in:** {{ $request->check_in_date->format(config('date.display')) }}  
**Check-out:** {{ $request->check_out_date->format(config('date.display')) }}

Our travel consultant will contact you shortly to confirm availability and final pricing.

@elseif($type === 'admin_new')
# New hotel booking request

**Reference:** {{ $request->reference_number }}  
**Hotel:** {{ $hotelName }}  
**Guest:** {{ $request->lead_guest_name }}  
**Email:** {{ $request->lead_guest_email }}

@elseif($type === 'status_changed')
# Status update

Your hotel request **{{ $request->reference_number }}** is now **{{ $request->statusLabel() }}**.

@elseif($type === 'document_uploaded')
# Document available

A **{{ $documentType }}** is now available for request **{{ $request->reference_number }}**.

@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
