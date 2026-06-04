@php
    $carName = $request->selected_vehicle['name'] ?? $request->rentalCar?->name ?? 'Rental Car';
@endphp
<x-mail::message>
@if($type === 'received')
# Car booking request received

Thank you for your request for **{{ $carName }}**.

**Reference:** {{ $request->reference_number }}  
**Pick-up:** {{ $request->pickup_date->format('M d, Y') }} at {{ $request->pickup_location }}  
**Return:** {{ $request->return_date->format('M d, Y') }} at {{ $request->dropoff_location ?: $request->pickup_location }}

Our travel consultant will contact you shortly to confirm availability and final pricing.

@elseif($type === 'admin_new')
# New car booking request

**Reference:** {{ $request->reference_number }}  
**Vehicle:** {{ $carName }}  
**Email:** {{ $request->contact_email }}  
**Phone:** {{ $request->contact_phone }}

@elseif($type === 'status_changed')
# Status update

Your car request **{{ $request->reference_number }}** is now **{{ $request->statusLabel() }}**.

@elseif($type === 'document_uploaded')
# Document available

A **{{ $documentType }}** is now available for request **{{ $request->reference_number }}**.

@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
