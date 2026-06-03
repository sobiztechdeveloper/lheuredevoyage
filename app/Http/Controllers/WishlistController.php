<?php

namespace App\Http\Controllers;

use App\Models\Cruise;
use App\Models\Hotel;
use App\Models\RentalCar;
use App\Models\TourPackage;
use App\Models\TravelInsurance;
use App\Models\UserWishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    private const TYPE_MAP = [
        'hotel' => Hotel::class,
        'tourpackage' => TourPackage::class,
        'cruise' => Cruise::class,
        'rentalcar' => RentalCar::class,
        'travelinsurance' => TravelInsurance::class,
    ];

    public function toggle(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => ['required', 'string'],
            'slug' => ['required', 'string'],
        ]);

        $modelClass = self::TYPE_MAP[$request->input('type')] ?? null;

        if (! $modelClass) {
            return back()->withErrors(['type' => 'Invalid item type.']);
        }

        $item = $modelClass::query()->active()->where('slug', $request->input('slug'))->firstOrFail();
        $user = $request->user();

        $existing = UserWishlist::query()
            ->where('user_id', $user->id)
            ->where('wishlistable_type', $modelClass)
            ->where('wishlistable_id', $item->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $message = 'Removed from wishlist.';
        } else {
            UserWishlist::query()->create([
                'user_id' => $user->id,
                'wishlistable_type' => $modelClass,
                'wishlistable_id' => $item->id,
            ]);
            $message = 'Added to wishlist.';
        }

        return back()->with('wishlist_message', $message);
    }

    public function destroy(UserWishlist $wishlist): RedirectResponse
    {
        abort_unless($wishlist->user_id === auth()->id(), 403);
        $wishlist->delete();

        return redirect()->route('my-wishlist')->with('success', 'Item removed from wishlist.');
    }
}
