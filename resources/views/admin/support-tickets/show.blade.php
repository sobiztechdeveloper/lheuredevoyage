@extends('layouts.admin.app')

@section('title', $ticket->reference)

@section('content')
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card mb-3">
    <div class="card-body">
        <h2 class="h5">{{ $ticket->reference }} — {{ $ticket->subject }}</h2>
        <p class="mb-0">{{ $ticket->user?->name }} ({{ $ticket->user?->email }}) — {{ ucfirst($ticket->category) }}</p>
    </div>
</div>
<form method="POST" action="{{ route('admin.support-tickets.update', $ticket) }}" class="card mb-3">
    @csrf @method('PUT')
    <div class="card-body row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                @foreach($statuses as $s)
                    <option value="{{ $s }}" @selected($ticket->status === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Priority</label>
            <select name="priority" class="form-select">
                @foreach(\App\Models\SupportTicket::PRIORITIES as $p)
                    <option value="{{ $p }}" @selected($ticket->priority === $p)>{{ ucfirst($p) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto"><button class="btn btn-primary">Update</button></div>
    </div>
</form>
<div class="card mb-3">
    <div class="card-header">Conversation</div>
    <div class="card-body">
        @foreach($ticket->replies as $reply)
            <div class="border rounded p-2 mb-2 {{ $reply->is_staff ? 'bg-light' : '' }}">
                <strong>{{ $reply->is_staff ? 'Staff' : 'Customer' }}</strong>
                <span class="text-muted small">{{ $reply->created_at->format('M d, Y H:i') }}</span>
                <p class="mb-0">{{ $reply->message }}</p>
            </div>
        @endforeach
    </div>
</div>
@if(!$ticket->isClosed())
<form method="POST" action="{{ route('admin.support-tickets.reply', $ticket) }}" class="card">
    @csrf
    <div class="card-body">
        <label class="form-label">Staff reply</label>
        <textarea name="message" class="form-control mb-2" rows="4" required></textarea>
        <button class="btn btn-primary">Send Reply</button>
    </div>
</form>
@endif
<a href="{{ route('admin.support-tickets.index') }}" class="btn btn-link mt-3">← Back</a>
@endsection
