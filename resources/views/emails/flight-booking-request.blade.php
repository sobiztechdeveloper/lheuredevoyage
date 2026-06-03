<x-mail::message>
@if($type === 'received')
# Booking request received

Thank you for your flight booking request.

**Reference:** {{ $request->booking_reference }}  
**Route:** {{ $request->routeLabel() }}  
**Travel date:** {{ $request->departure_date->format('M d, Y') }}

Our travel consultant will review availability and contact you shortly.
@elseif($type === 'status_changed')
# Booking status updated

Your flight request **{{ $request->booking_reference }}** has been updated.

**Route:** {{ $request->routeLabel() }}  
**New status:** {{ $request->statusLabel() }}

@if($previousStatus)
Previous status: {{ ucfirst(str_replace('_', ' ', $previousStatus)) }}
@endif
@elseif($type === 'document_uploaded')
# Document available

A {{ $documentType === 'ticket' ? 'ticket' : 'invoice' }} has been uploaded for booking **{{ $request->booking_reference }}**.

Please log in to your account or contact us for your documents.
@elseif($type === 'admin_new')
# New flight booking request

**{{ $request->booking_reference }}** — {{ $request->contact_name }} ({{ $request->email }})

**Route:** {{ $request->routeLabel() }}  
**Travel:** {{ $request->departure_date->format('M d, Y') }}  
**Passengers:** {{ $request->passengerCount() }}  
**Status:** {{ $request->statusLabel() }}
@endif

@if($type === 'admin_new')
<x-mail::button :url="route('admin.flight-requests.show', $request)">
Review in admin
</x-mail::button>
@elseif($request->user_id)
<x-mail::button :url="route('my-flight-bookings.show', $request)">
View booking
</x-mail::button>
@else
<x-mail::button :url="route('flight')">
Visit website
</x-mail::button>
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
