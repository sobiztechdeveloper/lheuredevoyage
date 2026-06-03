@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')
<div class="col-lg-9">
    <div class="user-profile-wrapper">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <div class="user-profile-card mb-3">
            <h4 class="user-profile-card-title">{{ $ticket->reference }} — {{ $ticket->subject }}</h4>
            <p class="mb-0"><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $ticket->status)) }} |
                <strong>Category:</strong> {{ ucfirst($ticket->category) }}</p>
        </div>
        <div class="user-profile-card mb-3">
            <h5>Conversation</h5>
            @foreach($ticket->replies as $reply)
                <div class="border rounded p-3 mb-2 {{ $reply->is_staff ? 'bg-light' : '' }}">
                    <strong>{{ $reply->is_staff ? 'Support Team' : 'You' }}</strong>
                    <span class="text-muted small float-end">{{ $reply->created_at->format('M d, Y H:i') }}</span>
                    <p class="mb-0 mt-2">{{ $reply->message }}</p>
                </div>
            @endforeach
        </div>
        @if(!$ticket->isClosed())
        <div class="user-profile-card">
            <h5>Reply</h5>
            <form method="POST" action="{{ route('support-tickets.reply', $ticket) }}">
                @csrf
                <textarea name="message" class="form-control mb-2" rows="4" required></textarea>
                @error('message')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                <button type="submit" class="theme-btn">Send Reply</button>
            </form>
        </div>
        @endif
        <a href="{{ route('support-tickets.index') }}" class="btn btn-link mt-3">← Back to tickets</a>
    </div>
</div>
@endsection
