@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')
<div class="col-lg-9">
    <div class="user-profile-wrapper">
        <div class="user-profile-card">
            <h4 class="user-profile-card-title">New Support Ticket</h4>
            <form method="POST" action="{{ route('support-tickets.store') }}" class="mt-3">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Subject</label>
                    <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" required>
                    @error('subject')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" @selected(old('category') === $cat)>{{ ucfirst($cat) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Priority</label>
                        <select name="priority" class="form-select">
                            @foreach($priorities as $p)
                                <option value="{{ $p }}" @selected(old('priority', 'normal') === $p)>{{ ucfirst($p) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Message</label>
                    <textarea name="message" class="form-control" rows="6" required>{{ old('message') }}</textarea>
                    @error('message')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="theme-btn">Submit Ticket</button>
                <a href="{{ route('support-tickets.index') }}" class="btn btn-link">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
