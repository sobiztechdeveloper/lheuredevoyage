@props(['item', 'bookableType'])

<form method="POST" action="{{ route('bookings.store') }}" class="booking-form mb-4">
    @csrf
    <input type="hidden" name="bookable_type" value="{{ $bookableType }}">
    <input type="hidden" name="bookable_slug" value="{{ $item->slug }}">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Full Name *</label>
            <input type="text" name="guest_name" class="form-control" value="{{ old('guest_name', auth()->user()?->name) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Email *</label>
            <input type="email" name="guest_email" class="form-control" value="{{ old('guest_email', auth()->user()?->email) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text" name="guest_phone" class="form-control" value="{{ old('guest_phone', auth()->user()?->profile?->phone) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Travel Date</label>
            <input type="date" name="travel_date" class="form-control" value="{{ old('travel_date') }}">
        </div>
        <div class="col-12">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
        </div>
        <div class="col-12">
            @guest
                <p class="text-muted">Please <a href="{{ route('login') }}">log in</a> to complete your booking.</p>
            @else
                <button type="submit" class="theme-btn">Confirm Booking — {{ $item->formatted_price }}</button>
            @endguest
        </div>
    </div>
</form>
