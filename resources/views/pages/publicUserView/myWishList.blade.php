@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')

<div class="col-lg-9">
    <div class="user-profile-wrapper">
        @if(session('success') || session('wishlist_message'))
            <div class="alert alert-success">{{ session('success') ?? session('wishlist_message') }}</div>
        @endif
        <div class="user-profile-card">
            <h4 class="user-profile-card-title">My Wishlist ({{ $wishlists->total() }})</h4>
            <div class="row mt-30 g-4">
                @forelse($wishlists as $wishlist)
                    @if($wishlist->wishlistable)
                        @php
                            $routePrefix = match(class_basename($wishlist->wishlistable)) {
                                'Hotel' => 'hotel',
                                'TourPackage' => 'tourpackage',
                                'Cruise' => 'cruise',
                                'RentalCar' => 'rentalcar',
                                'TravelInsurance' => 'travelinsurance',
                                default => 'hotel',
                            };
                            $bookableType = $routePrefix;
                        @endphp
                        <div class="col-md-6 col-lg-4 position-relative">
                            <x-catalog-card :item="$wishlist->wishlistable" :routePrefix="$routePrefix" :bookableType="$bookableType" />
                            <form method="POST" action="{{ route('wishlist.destroy', $wishlist) }}" class="position-absolute top-0 end-0 m-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Remove">&times;</button>
                            </form>
                        </div>
                    @endif
                @empty
                    <div class="col-12">
                        <p class="text-muted">Your wishlist is empty. Browse <a href="{{ route('hotel') }}">hotels</a>, <a href="{{ route('tourpackage') }}">packages</a>, or <a href="{{ route('cruise') }}">cruises</a> and save your favorites.</p>
                    </div>
                @endforelse
            </div>
            {{ $wishlists->links() }}
        </div>
    </div>
</div>

@endsection
