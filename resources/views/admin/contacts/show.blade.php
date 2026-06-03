@extends('layouts.admin.app')

@section('title', 'Message')

@section('content')
<div class="card p-4">
    <p><strong>From:</strong> {{ $contact->name }} &lt;{{ $contact->email }}&gt;</p>
    @if($contact->phone)<p><strong>Phone:</strong> {{ $contact->phone }}</p>@endif
    @if($contact->subject)<p><strong>Subject:</strong> {{ $contact->subject }}</p>@endif
    <hr>
    <p>{{ $contact->message }}</p>
    <form action="{{ route('admin.inquiries.destroy', $contact) }}" method="POST" class="mt-3" onsubmit="return confirm('Delete message?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
    </form>
</div>
@endsection
