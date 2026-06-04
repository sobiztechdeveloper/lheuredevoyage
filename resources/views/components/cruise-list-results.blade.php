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
        <div class="col-12">
            <div class="text-center py-5 px-3 rounded" style="background:#f4f7fc;">
                <i class="far fa-ship fa-3x text-primary mb-3"></i>
                <h5>No cruises match your search</h5>
                <p class="text-muted mb-3">Try another destination, adjust filters, or browse all cruises.</p>
                <a href="{{ route('cruise') }}" class="theme-btn me-2">View All Cruises</a>
                <a href="{{ route('cruise.quote.wizard') }}" class="fbw-btn-outline d-inline-block px-4 py-2 rounded">Request Quote</a>
            </div>
        </div>
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
