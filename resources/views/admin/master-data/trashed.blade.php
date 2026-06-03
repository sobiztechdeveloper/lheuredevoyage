@extends('layouts.admin.app')
@section('title', 'Trashed — '.$config['label'])
@section('content')
<div class="admin-page-header">
    <div>
        <h1>Trashed: {{ $config['label'] }}</h1>
        <p class="text-muted small mb-0">Restore deleted master data entries</p>
    </div>
    <a href="{{ route('admin.master-data.'.$type.'.index') }}" class="btn btn-admin-outline btn-sm"><i class="far fa-arrow-left me-1"></i> Back to list</a>
</div>

<div class="admin-table-card">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Name</th><th>Deleted</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->deleted_at->format('M d, Y H:i') }}</td>
                    <td class="text-end">
                        <form method="POST" action="{{ route('admin.master-data.'.$type.'.restore', $item->id) }}">
                            @csrf
                            <button class="btn btn-sm btn-admin-secondary"><i class="far fa-rotate-left me-1"></i> Restore</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center py-5 text-muted">No trashed items.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())
    <div class="admin-table-footer">{{ $items->links() }}</div>
    @endif
</div>
@endsection
