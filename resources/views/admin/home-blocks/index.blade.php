@extends('layouts.admin.app')
@section('title', 'Home Blocks')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h2 class="h4 mb-0">Homepage Blocks</h2>
    <a href="{{ route('admin.home-blocks.create') }}" class="btn btn-primary btn-sm">Add block</a>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <select name="section" class="form-select">
            <option value="">All sections</option>
            @foreach($sections as $key => $label)
                <option value="{{ $key }}" @selected($filterSection === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <input type="search" name="q" class="form-control" placeholder="Search..." value="{{ $search }}">
    </div>
    <div class="col-md-2">
        <button class="btn btn-outline-secondary w-100" type="submit">Filter</button>
    </div>
</form>

<div class="card">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Section</th>
                <th>Title</th>
                <th>Order</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($blocks as $block)
                <tr>
                    <td><span class="badge bg-secondary">{{ $block->section }}</span></td>
                    <td>{{ Str::limit($block->title ?? $block->subtitle ?? '—', 50) }}</td>
                    <td>{{ $block->sort_order }}</td>
                    <td>{{ $block->is_active ? 'Active' : 'Hidden' }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.home-blocks.edit', $block) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('admin.home-blocks.destroy', $block) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-4">No blocks found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $blocks->links() }}
@endsection
