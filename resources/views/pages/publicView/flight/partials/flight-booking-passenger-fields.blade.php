@foreach($passengerSlots as $index => $slot)
    @php
        $prefix = "passengers.{$index}";
        $oldPassenger = old("passengers.{$index}", []);
    @endphp
    <div class="fbw-passenger-block" data-passenger-index="{{ $index }}" data-passenger-label="{{ $slot['label'] }}">
        <h5 class="fbw-passenger-heading">{{ $slot['label'] }}</h5>
        <input type="hidden" name="passengers[{{ $index }}][passenger_type]" value="{{ $slot['type'] }}">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Title <span class="text-danger">*</span></label>
                <select name="passengers[{{ $index }}][title]" class="form-select @error($prefix.'.title') is-invalid @enderror" required>
                    <option value="">Select</option>
                    @foreach(['Mr', 'Mrs', 'Ms', 'Master', 'Miss'] as $title)
                        <option value="{{ $title }}" @selected(($oldPassenger['title'] ?? '') === $title)>{{ $title }}</option>
                    @endforeach
                </select>
                @error($prefix.'.title')<span class="fbw-invalid-inline">{{ $message }}</span>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">First Name <span class="text-danger">*</span></label>
                <input type="text" name="passengers[{{ $index }}][first_name]" class="form-control @error($prefix.'.first_name') is-invalid @enderror"
                    value="{{ $oldPassenger['first_name'] ?? '' }}" required>
                @error($prefix.'.first_name')<span class="fbw-invalid-inline">{{ $message }}</span>@enderror
            </div>
            <div class="col-md-5">
                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                <input type="text" name="passengers[{{ $index }}][last_name]" class="form-control @error($prefix.'.last_name') is-invalid @enderror"
                    value="{{ $oldPassenger['last_name'] ?? '' }}" required>
                @error($prefix.'.last_name')<span class="fbw-invalid-inline">{{ $message }}</span>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                <input type="date" name="passengers[{{ $index }}][date_of_birth]" class="form-control @error($prefix.'.date_of_birth') is-invalid @enderror"
                    value="{{ $oldPassenger['date_of_birth'] ?? '' }}" required>
                @error($prefix.'.date_of_birth')<span class="fbw-invalid-inline">{{ $message }}</span>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Gender <span class="text-danger">*</span></label>
                <select name="passengers[{{ $index }}][gender]" class="form-select @error($prefix.'.gender') is-invalid @enderror" required>
                    <option value="">Select</option>
                    <option value="male" @selected(($oldPassenger['gender'] ?? '') === 'male')>Male</option>
                    <option value="female" @selected(($oldPassenger['gender'] ?? '') === 'female')>Female</option>
                    <option value="other" @selected(($oldPassenger['gender'] ?? '') === 'other')>Other</option>
                </select>
                @error($prefix.'.gender')<span class="fbw-invalid-inline">{{ $message }}</span>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Nationality <span class="text-danger">*</span></label>
                <input type="text" name="passengers[{{ $index }}][nationality]" class="form-control @error($prefix.'.nationality') is-invalid @enderror"
                    value="{{ $oldPassenger['nationality'] ?? '' }}" required>
                @error($prefix.'.nationality')<span class="fbw-invalid-inline">{{ $message }}</span>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Passport Number <span class="text-danger">*</span></label>
                <input type="text" name="passengers[{{ $index }}][passport_number]" class="form-control @error($prefix.'.passport_number') is-invalid @enderror"
                    value="{{ $oldPassenger['passport_number'] ?? '' }}" required>
                @error($prefix.'.passport_number')<span class="fbw-invalid-inline">{{ $message }}</span>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Passport Expiry <span class="text-danger">*</span></label>
                <input type="date" name="passengers[{{ $index }}][passport_expiry]" class="form-control @error($prefix.'.passport_expiry') is-invalid @enderror"
                    value="{{ $oldPassenger['passport_expiry'] ?? '' }}" required>
                @error($prefix.'.passport_expiry')<span class="fbw-invalid-inline">{{ $message }}</span>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Passport Issuing Country</label>
                <input type="text" name="passengers[{{ $index }}][passport_country]" class="form-control @error($prefix.'.passport_country') is-invalid @enderror"
                    value="{{ $oldPassenger['passport_country'] ?? '' }}">
                @error($prefix.'.passport_country')<span class="fbw-invalid-inline">{{ $message }}</span>@enderror
            </div>
            @include('pages.publicView.flight.partials.flight-booking-passport-upload', ['index' => $index, 'prefix' => $prefix])
        </div>
    </div>
@endforeach
