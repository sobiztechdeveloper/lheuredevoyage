<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CruiseController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\FlightBookingRequestController;
use App\Http\Controllers\UserFlightBookingRequestController;
use App\Http\Controllers\UserQuoteController;
use App\Http\Controllers\FlightSearchController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentalCarController;
use App\Http\Controllers\TourPackageController;
use App\Http\Controllers\TravelInsuranceController;
use App\Http\Controllers\UserBookingHistoryController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserMyBookingController;
use App\Http\Controllers\UserNotificationController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserSettingController;
use App\Http\Controllers\UserWalletController;
use App\Http\Controllers\UserWishlistController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\UserBookingDetailController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', RobotsController::class)->name('robots');

Route::get('/flights', [FlightController::class, 'index'])->name('flight');
Route::get('/flights/search', [FlightController::class, 'search'])->name('flight.search');
Route::post('/flights/search', [FlightSearchController::class, 'store'])->name('flight.search.submit');
Route::post('/flights/results/{flightSearch}/update', [FlightSearchController::class, 'update'])->name('flight.search.update');
Route::get('/flights/results/{flightSearch}', [FlightSearchController::class, 'results'])->name('flight.search.results');
Route::get('/flight-booking/create/{flightSearchResult}', [FlightBookingRequestController::class, 'create'])->name('flight.booking.create');
Route::post('/flight-booking/create/{flightSearchResult}', [FlightBookingRequestController::class, 'store'])->name('flight.booking.store');
Route::get('/flight-booking/confirmation/{flightBookingRequest}', [FlightBookingRequestController::class, 'confirmation'])->name('flight.booking.confirmation');
Route::get('/flights/results/{flightSearch}/offer/{offerId}', [FlightSearchController::class, 'showOffer'])->name('flight.search.offer');
Route::get('/flights/results/{flightSearch}/offer/{offerId}/fare-rules', [FlightSearchController::class, 'fareRules'])->name('flight.search.offer.fare-rules');
Route::get('/flights/{slug}', [FlightController::class, 'show'])->name('flight.show');
Route::get('/flights/{slug}/book', [FlightController::class, 'book'])->name('flight.book');

Route::get('/hotels', [HotelController::class, 'index'])->name('hotel');
Route::get('/hotels/search', [HotelController::class, 'search'])->name('hotel.search');
Route::get('/hotels/{slug}', [HotelController::class, 'show'])->name('hotel.show');
Route::get('/hotels/{slug}/book', [HotelController::class, 'book'])->name('hotel.book');

Route::get('/cruises', [CruiseController::class, 'index'])->name('cruise');
Route::get('/cruises/search', [CruiseController::class, 'search'])->name('cruise.search');
Route::get('/cruises/{slug}', [CruiseController::class, 'show'])->name('cruise.show');
Route::get('/cruises/{slug}/book', [CruiseController::class, 'book'])->name('cruise.book');

Route::get('/cars', [RentalCarController::class, 'index'])->name('rentalcar');
Route::get('/cars/search', [RentalCarController::class, 'search'])->name('rentalcar.search');
Route::get('/cars/{slug}', [RentalCarController::class, 'show'])->name('rentalcar.show');
Route::get('/cars/{slug}/book', [RentalCarController::class, 'book'])->name('rentalcar.book');

Route::get('/travelinsurances', [TravelInsuranceController::class, 'index'])->name('travelinsurance');
Route::get('/travelinsurances/search', [TravelInsuranceController::class, 'search'])->name('travelinsurance.search');
Route::get('/travelinsurances/{slug}', [TravelInsuranceController::class, 'show'])->name('travelinsurance.show');
Route::get('/travelinsurances/{slug}/book', [TravelInsuranceController::class, 'book'])->name('travelinsurance.book');

Route::get('/tourpackages', [TourPackageController::class, 'index'])->name('tourpackage');
Route::get('/holidaypackages/search', [TourPackageController::class, 'search'])->name('tourpackage.search');
Route::get('/tourpackages/{slug}', [TourPackageController::class, 'show'])->name('tourpackage.show');
Route::get('/tourpackages/{slug}/book', [TourPackageController::class, 'book'])->name('tourpackage.book');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::post('/bookings', [BookingController::class, 'store'])->middleware('auth')->name('bookings.store');

require __DIR__.'/auth.php';

Route::middleware(['auth', 'active'])->group(function () {
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::get('/dashboard', fn () => redirect()->route('my-dashboard'))->name('dashboard');
    Route::get('/my-bookings', [UserBookingHistoryController::class, 'index'])->name('booking-history');
    Route::get('/my-dashboard', [UserDashboardController::class, 'index'])->name('my-dashboard');
    Route::get('/my-bookings-list', [UserMyBookingController::class, 'index'])->name('my-bookings-list');
    Route::get('/my-flight-bookings', [UserFlightBookingRequestController::class, 'index'])->name('my-flight-bookings.index');
    Route::get('/my-flight-bookings/{flightBookingRequest}', [UserFlightBookingRequestController::class, 'show'])->name('my-flight-bookings.show');

    Route::get('/my-quotes', [UserQuoteController::class, 'index'])->name('my-quotes.index');
    Route::get('/my-quotes/{quote}', [UserQuoteController::class, 'show'])->name('my-quotes.show');
    Route::post('/my-quotes/{quote}/accept', [UserQuoteController::class, 'accept'])->name('my-quotes.accept');
    Route::post('/my-quotes/{quote}/reject', [UserQuoteController::class, 'reject'])->name('my-quotes.reject');
    Route::get('/my-quotes/{quote}/pdf', [UserQuoteController::class, 'pdf'])->name('my-quotes.pdf');
    Route::get('/my-bookings/{booking}', [UserBookingDetailController::class, 'show'])->name('my-bookings.show');
    Route::get('/my-bookings/{booking}/invoice', [UserBookingDetailController::class, 'invoice'])->name('my-bookings.invoice');
    Route::get('/my-bookings/{booking}/invoice/pdf', [UserBookingDetailController::class, 'invoicePdf'])->name('my-bookings.invoice.pdf');
    Route::post('/my-bookings/{booking}/cancel-request', [UserBookingDetailController::class, 'requestCancellation'])->name('my-bookings.cancel-request');

    Route::get('/support-tickets', [SupportTicketController::class, 'index'])->name('support-tickets.index');
    Route::get('/support-tickets/create', [SupportTicketController::class, 'create'])->name('support-tickets.create');
    Route::post('/support-tickets', [SupportTicketController::class, 'store'])->name('support-tickets.store');
    Route::get('/support-tickets/{supportTicket}', [SupportTicketController::class, 'show'])->name('support-tickets.show');
    Route::post('/support-tickets/{supportTicket}/reply', [SupportTicketController::class, 'reply'])->name('support-tickets.reply');
    Route::get('/my-notifications', [UserNotificationController::class, 'index'])->name('my-notifications');
    Route::get('/my-settings', [UserSettingController::class, 'index'])->name('my-settings');
    Route::post('/my-settings', [UserSettingController::class, 'update'])->name('my-settings.update');
    Route::get('/my-wallet', [UserWalletController::class, 'index'])->name('my-wallet');
    Route::get('/my-wishlist', [UserWishlistController::class, 'index'])->name('my-wishlist');
    Route::get('/my-profile', [UserProfileController::class, 'index'])->name('my-profile');
    Route::post('/my-profile', [UserProfileController::class, 'update'])->name('my-profile.update');
});

