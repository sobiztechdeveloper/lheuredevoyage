@extends('layouts.admin.app')

@section('title', 'Support Tickets')

@section('content')
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4"><input type="search" name="q" class="form-control" placeholder="Search" value="{{ $search }}"></div>
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">All statuses</option>
            @foreach($statuses as $s)
                <option value="{{ $s }}" @selected($filterStatus === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto"><button class="btn btn-primary">Filter</button></div>
</form>
<div class="card">
    <table class="table mb-0">
        <thead><tr><th>Reference</th><th>Customer</th><th>Subject</th><th>Status</th><th>Updated</th><th></th></tr></thead>
        <tbody>
            @foreach($tickets as $ticket)
            <tr>
                <td>{{ $ticket->reference }}</td>
                <td>{{ $ticket->user?->name }}</td>
                <td>{{ $ticket->subject }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</td>
                <td>{{ $ticket->updated_at->format('M d, Y') }}</td>
                <td class="text-end"><a href="{{ route('admin.support-tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">View</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $tickets->links() }}
@endsection
