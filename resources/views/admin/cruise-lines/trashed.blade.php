@extends('layouts.admin.app')
@section('title', 'Trashed Cruise Lines')
@section('content')
<div class="admin-page-header">
    <div>
        <h1>Trashed Cruise Lines</h1>
    </div>
    <a href="{{ route('admin.cruise-lines.index') }}" class="btn btn-admin-outline btn-sm">Back to list</a>
</div>

<div class="admin-table-card">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr><th>Name</th><th>Deleted</th><th class="text-end">Actions</th></tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td class="small text-muted">{{ $item->deleted_at?->format(config('date.display_datetime')) }}</td>
                    <td class="text-end">
                        <form method="POST" action="{{ route('admin.cruise-lines.restore', $item->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-admin-primary">Restore</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center py-5 text-muted">Trash is empty.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())
    <div class="admin-table-footer">{{ $items->links() }}</div>
    @endif
</div>
@endsection
