@props(['items', 'searchQuery' => [], 'showHeader' => true])

@if($showHeader)
<div class="booking-sort mb-4">
    <h5 class="mb-0">{{ $items->total() }} {{ Str::plural('Cruise', $items->total()) }} Found</h5>
</div>
@endif

<div class="row g-4">
    @forelse($items as $item)
        <x-cruise-card :item="$item" :search-query="$searchQuery" />
    @empty
        <x-catalog-empty-state type="cruise" :search-query="$searchQuery" />
    @endforelse
</div>

@if($items->hasPages())
    <div class="pagination-area mt-4">
        {{ $items->withQueryString()->links() }}
        <div class="pagination-showing">
            <p>Showing {{ $items->firstItem() }} – {{ $items->lastItem() }} of {{ $items->total() }}</p>
        </div>
    </div>
@endif
