<?php

use App\Http\Controllers\AboutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CruiseController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentalCarController;
use App\Http\Controllers\TravelInsuranceController;
use App\Http\Controllers\TourPackageController;
use App\Http\Controllers\UserBookingHistoryController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserMyBookingController;
use App\Http\Controllers\UserNotificationController;
use App\Http\Controllers\UserSettingController;
use App\Http\Controllers\UserWalletController;
use App\Http\Controllers\UserWishlistController;
use App\Http\Controllers\UserProfileController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/flights', [FlightController::class, 'index'])->name('flight');
Route::get('/hotels', [HotelController::class, 'index'])->name('hotel');
Route::get('/cruises', [CruiseController::class, 'index'])->name('cruise');
Route::get('/cars', [RentalCarController::class, 'index'])->name('rentalcar');
Route::get('/travelinsurances', [TravelInsuranceController::class, 'index'])->name('travelinsurance');
Route::get('/tourpackages', [TourPackageController::class, 'index'])->name('tourpackage');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::get('/flights/search', [FlightController::class, 'search'])->name('flight.search');
Route::get('/hotels/search', [HotelController::class, 'search'])->name('hotel.search');
Route::get('/cars/search', [RentalCarController::class, 'search'])->name('rentalcar.search');
Route::get('/cruises/search', [CruiseController::class, 'search'])->name('cruise.search');
Route::get('/travelinsurances/search', [TravelInsuranceController::class, 'search'])->name('travelinsurance.search');
Route::get('/holidaypackages/search', [TourPackageController::class, 'search'])->name('tourpackage.search');
require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {

    Route::get('/my-bookings', [UserBookingHistoryController::class, 'index'])
        ->name('booking-history');

    Route::get('/my-dashboard', [UserDashboardController::class, 'index'])
        ->name('my-dashboard');

    Route::get('/my-bookings-list', [UserMyBookingController::class, 'index'])
        ->name('my-bookings-list');

    Route::get('/my-notifications', [UserNotificationController::class, 'index'])
        ->name('my-notifications');

    Route::get('/my-settings', [UserSettingController::class, 'index'])
        ->name('my-settings');

    Route::get('/my-wallet', [UserWalletController::class, 'index'])
        ->name('my-wallet');

    Route::get('/my-wishlist', [UserWishlistController::class, 'index'])
        ->name('my-wishlist');

    Route::get('/my-profile', [UserProfileController::class, 'index'])
        ->name('my-profile');
});

// Route::get('/my-bookings', [UserBookingHistoryController::class, 'index'])->name('booking-history');
// Route::get('/my-dashboard', [UserDashboardController::class, 'index'])->name('my-dashboard');
// Route::get('/my-bookings-list', [UserMyBookingController::class, 'index'])->name('my-bookings-list');
// Route::get('/my-notifications', [UserNotificationController::class, 'index'])->name('my-notifications');
// Route::get('/my-settings', [UserSettingController::class, 'index'])->name('my-settings');
// Route::get('/my-wallet', [UserWalletController::class, 'index'])->name('my-wallet');
// Route::get('/my-wishlist', [UserWishlistController::class, 'index'])->name('my-wishlist');
// Route::get('/my-profile', [UserProfileController::class, 'index'])->name('my-profile');
