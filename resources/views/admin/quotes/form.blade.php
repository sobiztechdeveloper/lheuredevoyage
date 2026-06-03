@extends('layouts.admin.app')

@section('title', ($isEdit ? 'Edit' : 'Create').' Quote')

@section('content')
<div class="admin-page-header mb-4">
    <div>
        <h1>{{ $isEdit ? 'Edit Quote' : 'Create Quote' }}</h1>
        @if($isEdit)<p class="text-muted small mb-0">{{ $quote->quote_number }}</p>@endif
    </div>
</div>

<form method="POST" action="{{ $isEdit ? route('admin.quotes.update', $quote) : route('admin.quotes.store') }}" id="quote-form">
    @csrf
    @if($isEdit) @method('PUT') @endif
    @include('admin.quotes.partials.form')
</form>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/quote-admin.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/js/admin-quote-form.js') }}"></script>
@endpush
