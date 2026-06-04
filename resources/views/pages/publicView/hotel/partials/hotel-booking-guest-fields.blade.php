@foreach($guestSlots as $index => $slot)
@php $prefix = "guests.{$index}"; $oldGuest = old("guests.{$index}", []); @endphp
<div class="fbw-passenger-block mb-4">
    <h5 class="fbw-passenger-heading">{{ $slot['label'] }}</h5>
    <input type="hidden" name="guests[{{ $index }}][guest_type]" value="{{ $slot['type'] }}">
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Title</label>
            <select name="guests[{{ $index }}][title]" class="form-select">
                <option value="">—</option>
                @foreach(['Mr','Mrs','Ms','Master','Miss'] as $t)
                    <option value="{{ $t }}" @selected(($oldGuest['title'] ?? '') === $t)>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">First Name <span class="text-danger">*</span></label>
            <input type="text" name="guests[{{ $index }}][first_name]" class="form-control" value="{{ $oldGuest['first_name'] ?? '' }}" required>
        </div>
        <div class="col-md-5">
            <label class="form-label">Last Name <span class="text-danger">*</span></label>
            <input type="text" name="guests[{{ $index }}][last_name]" class="form-control" value="{{ $oldGuest['last_name'] ?? '' }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Date of Birth</label>
            <input type="date" name="guests[{{ $index }}][date_of_birth]" class="form-control" value="{{ $oldGuest['date_of_birth'] ?? '' }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Nationality</label>
            <input type="text" name="guests[{{ $index }}][nationality]" class="form-control" value="{{ $oldGuest['nationality'] ?? '' }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Passport Number</label>
            <input type="text" name="guests[{{ $index }}][passport_number]" class="form-control" value="{{ $oldGuest['passport_number'] ?? '' }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Passport Expiry</label>
            <input type="date" name="guests[{{ $index }}][passport_expiry]" class="form-control" value="{{ $oldGuest['passport_expiry'] ?? '' }}">
        </div>
        <div class="col-md-8">
            <label class="form-label">Passport Copy</label>
            <input type="file" name="guests[{{ $index }}][passport_file]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
        </div>
    </div>
</div>
@endforeach
