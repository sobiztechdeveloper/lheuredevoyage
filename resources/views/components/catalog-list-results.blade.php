@props(['items', 'routePrefix', 'label' => 'Results', 'showHeader' => true, 'searchQuery' => []])

@if($showHeader)
<div class="booking-sort">
    <h5>{{ $items->total() }} {{ $label }} Found</h5>
</div>
@endif

<div class="row">
    @forelse($items as $item)
        <x-catalog-card :item="$item" :route-prefix="$routePrefix" :search-query="$searchQuery" />
    @empty
        <div class="col-12">
            <p class="text-center py-5">No results found. Try adjusting your search.</p>
        </div>
    @endforelse
</div>

@if($items->hasPages())
    <div class="pagination-area mt-4">
        {{ $items->withQueryString()->links() }}
        <div class="pagination-showing">
            <p>Showing {{ $items->firstItem() }} - {{ $items->lastItem() }} of {{ $items->total() }}</p>
        </div>
    </div>
@endif
