@extends('layouts.admin.app')

@section('title', 'Activity Logs')

@section('content')
<form method="GET" class="mb-3">
    <input type="search" name="q" class="form-control w-auto d-inline-block" placeholder="Filter by action" value="{{ $search }}">
    <button class="btn btn-primary btn-sm">Search</button>
</form>
<div class="card">
    <table class="table table-sm mb-0">
        <thead><tr><th>When</th><th>User</th><th>Action</th><th>Subject</th><th>IP</th></tr></thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td>{{ $log->created_at->format('M d, Y H:i') }}</td>
                <td>{{ $log->user?->name ?? '—' }}</td>
                <td><code>{{ $log->action }}</code></td>
                <td>{{ $log->subject_type ? class_basename($log->subject_type).' #'.$log->subject_id : '—' }}</td>
                <td>{{ $log->ip_address }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $logs->links() }}
@endsection
