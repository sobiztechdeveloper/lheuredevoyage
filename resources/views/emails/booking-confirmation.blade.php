<x-mail::message>
# {{ $isStatusUpdate ? 'Booking Update' : 'Booking Received' }}

Hello {{ $booking->user->name }},

@if($isStatusUpdate)
Your booking **{{ $booking->reference }}** status is now **{{ ucfirst($booking->status) }}**.
@else
Thank you for booking with {{ config('app.name') }}. We have received your request.
@endif

**Reference:** {{ $booking->reference }}  
**Item:** {{ $booking->bookable?->title ?? 'Travel product' }}  
**Amount:** {{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}  
**Status:** {{ ucfirst($booking->status) }}  
@if($booking->booked_at)
**Date:** {{ $booking->booked_at->format('M d, Y H:i') }}
@endif

<x-mail::button :url="route('my-bookings-list')">
View My Bookings
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
