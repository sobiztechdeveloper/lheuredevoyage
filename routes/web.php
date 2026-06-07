<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingRequestFileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CookieConsentController;
use App\Http\Controllers\LegalPageController;
use App\Http\Controllers\CruiseBookingRequestController;
use App\Http\Controllers\CruiseController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\FlightBookingRequestController;
use App\Http\Controllers\UserFlightBookingRequestController;
use App\Http\Controllers\UserCruiseBookingRequestController;
use App\Http\Controllers\UserCarBookingRequestController;
use App\Http\Controllers\UserInsuranceBookingRequestController;
use App\Http\Controllers\UserQuoteController;
use App\Http\Controllers\FlightSearchController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\HotelSearchController;
use App\Http\Controllers\HotelBookingRequestController;
use App\Http\Controllers\CarBookingRequestController;
use App\Http\Controllers\InsuranceBookingRequestController;
use App\Http\Controllers\UserHotelBookingRequestController;
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
Route::post('/currency', [CurrencyController::class, 'switch'])->name('currency.switch');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', RobotsController::class)->name('robots');

Route::get('/flight/autocomplete', [\App\Http\Controllers\Api\FlightController::class, 'autocomplete'])->name('flight.autocomplete');
Route::get('/flight/search', [\App\Http\Controllers\Api\FlightController::class, 'search'])->name('flight.serpapi.search');

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
Route::post('/hotels/search', [HotelSearchController::class, 'store'])->name('hotel.search.submit');
Route::post('/hotels/results/{hotelSearch}/update', [HotelSearchController::class, 'update'])->name('hotel.search.update');
Route::get('/hotels/results/{hotelSearch}', [HotelSearchController::class, 'results'])->name('hotel.search.results');
Route::get('/hotels/{hotel}/booking-request', [HotelBookingRequestController::class, 'create'])->name('hotel.booking.create');
Route::post('/hotel-booking-request', [HotelBookingRequestController::class, 'store'])->name('hotel.booking.store');
Route::get('/hotel-booking/confirmation/{hotelBookingRequest}', [HotelBookingRequestController::class, 'confirmation'])->name('hotel.booking.confirmation');
Route::get('/hotels/{slug}', [HotelController::class, 'show'])->name('hotel.show');
Route::get('/hotels/{slug}/book', [HotelController::class, 'book'])->name('hotel.book');

Route::get('/cruises', [CruiseController::class, 'index'])->name('cruise');
Route::get('/cruises/search', [CruiseController::class, 'search'])->name('cruise.search');
Route::get('/cruises/request-quote/{cruise:slug?}', [CruiseBookingRequestController::class, 'quoteWizard'])
    ->name('cruise.quote.wizard');
Route::get('/cruises/{cruise}/booking-request', [CruiseBookingRequestController::class, 'create'])->name('cruise.booking.create');
Route::post('/cruise-booking-request', [CruiseBookingRequestController::class, 'store'])->name('cruise.booking.store');
Route::get('/cruise-booking/confirmation/{cruiseBookingRequest}', [CruiseBookingRequestController::class, 'confirmation'])->name('cruise.booking.confirmation');
Route::get('/cruises/{slug}', [CruiseController::class, 'show'])->name('cruise.show');
Route::get('/cruises/{slug}/book', [CruiseController::class, 'book'])->name('cruise.book');

Route::get('/cars', [RentalCarController::class, 'index'])->name('rentalcar');
Route::get('/cars/search', [RentalCarController::class, 'search'])->name('rentalcar.search');
Route::get('/cars/{rentalCar}/booking-request', [CarBookingRequestController::class, 'create'])->name('rentalcar.booking.create');
Route::post('/rentalcar-booking-request', [CarBookingRequestController::class, 'store'])->name('rentalcar.booking.store');
Route::get('/rentalcar-booking/confirmation/{carBookingRequest}', [CarBookingRequestController::class, 'confirmation'])->name('rentalcar.booking.confirmation');
Route::get('/cars/{slug}', [RentalCarController::class, 'show'])->name('rentalcar.show');
Route::get('/cars/{slug}/book', [RentalCarController::class, 'book'])->name('rentalcar.book');

Route::get('/travelinsurances', [TravelInsuranceController::class, 'index'])->name('travelinsurance');
Route::get('/travelinsurances/search', [TravelInsuranceController::class, 'search'])->name('travelinsurance.search');
Route::get('/travelinsurances/request-quote/{travelInsurance:slug?}', [InsuranceBookingRequestController::class, 'quoteWizard'])
    ->name('travelinsurance.quote.wizard');
Route::get('/travelinsurances/{travelInsurance}/booking-request', [InsuranceBookingRequestController::class, 'create'])->name('travelinsurance.booking.create');
Route::post('/travelinsurance-booking-request', [InsuranceBookingRequestController::class, 'store'])->name('travelinsurance.booking.store');
Route::get('/travelinsurance-booking/confirmation/{insuranceBookingRequest}', [InsuranceBookingRequestController::class, 'confirmation'])->name('travelinsurance.booking.confirmation');
Route::get('/travelinsurances/{slug}', [TravelInsuranceController::class, 'show'])->name('travelinsurance.show');
Route::get('/travelinsurances/{slug}/book', [TravelInsuranceController::class, 'book'])->name('travelinsurance.book');

Route::get('/tourpackages', [TourPackageController::class, 'index'])->name('tourpackage');
Route::get('/holidaypackages/search', [TourPackageController::class, 'search'])->name('tourpackage.search');
Route::get('/tourpackages/{slug}', [TourPackageController::class, 'show'])->name('tourpackage.show');
Route::get('/tourpackages/{slug}/book', [TourPackageController::class, 'book'])->name('tourpackage.book');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::get('/cookie-settings', [CookieConsentController::class, 'settings'])->name('cookie-settings');
Route::post('/cookie-consent', [CookieConsentController::class, 'store'])->name('cookie-consent.store');
Route::get('/legal/{slug}', [LegalPageController::class, 'show'])->name('legal.show');

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
    Route::get('/my-hotel-bookings', [UserHotelBookingRequestController::class, 'index'])->name('my-hotel-bookings.index');
    Route::get('/my-hotel-bookings/{hotelBookingRequest}', [UserHotelBookingRequestController::class, 'show'])->name('my-hotel-bookings.show');
    Route::get('/my-cruise-bookings', [UserCruiseBookingRequestController::class, 'index'])->name('my-cruise-bookings.index');
    Route::get('/my-cruise-bookings/{cruiseBookingRequest}', [UserCruiseBookingRequestController::class, 'show'])->name('my-cruise-bookings.show');
    Route::get('/my-car-bookings', [UserCarBookingRequestController::class, 'index'])->name('my-car-bookings.index');
    Route::get('/my-car-bookings/{carBookingRequest}', [UserCarBookingRequestController::class, 'show'])->name('my-car-bookings.show');
    Route::get('/my-insurance-requests', [UserInsuranceBookingRequestController::class, 'index'])->name('my-insurance-requests.index');
    Route::get('/my-insurance-requests/{insuranceBookingRequest}', [UserInsuranceBookingRequestController::class, 'show'])->name('my-insurance-requests.show');

    Route::get('/booking-files/flight/{flightBookingRequest}/{document}', [BookingRequestFileController::class, 'flightDocument'])
        ->where('document', 'ticket|invoice')
        ->name('booking-files.flight.document');
    Route::get('/booking-files/flight/{flightBookingRequest}/passport/{passenger}', [BookingRequestFileController::class, 'flightPassport'])
        ->name('booking-files.flight.passport');
    Route::get('/booking-files/hotel/{hotelBookingRequest}/{document}', [BookingRequestFileController::class, 'hotelDocument'])
        ->where('document', 'voucher|invoice')
        ->name('booking-files.hotel.document');
    Route::get('/booking-files/cruise/{cruiseBookingRequest}/{document}', [BookingRequestFileController::class, 'cruiseDocument'])
        ->where('document', 'voucher|invoice|ticket|boarding|boarding_instructions|excursion|excursion_details')
        ->name('booking-files.cruise.document');
    Route::get('/booking-files/cruise/{cruiseBookingRequest}/uploads/{document}', [BookingRequestFileController::class, 'cruiseUploadedDocument'])
        ->name('booking-files.cruise.uploaded');
    Route::get('/booking-files/cruise/{cruiseBookingRequest}/passport/{passenger}', [BookingRequestFileController::class, 'cruisePassport'])
        ->name('booking-files.cruise.passport');
    Route::get('/booking-files/car/{carBookingRequest}/{document}', [BookingRequestFileController::class, 'carDocument'])
        ->where('document', 'voucher|invoice|rental_agreement')
        ->name('booking-files.car.document');
    Route::get('/booking-files/car/{carBookingRequest}/driver/{driver}/{file}', [BookingRequestFileController::class, 'carDriverFile'])
        ->where('file', 'passport|license')
        ->name('booking-files.car.driver');
    Route::get('/booking-files/insurance/{insuranceBookingRequest}/{document}', [BookingRequestFileController::class, 'insuranceDocument'])
        ->where('document', 'policy|invoice|coverage|coverage_certificate|claim_instructions')
        ->name('booking-files.insurance.document');
    Route::get('/booking-files/insurance/{insuranceBookingRequest}/uploads/{document}', [BookingRequestFileController::class, 'insuranceUploadedDocument'])
        ->name('booking-files.insurance.uploaded');

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
    Route::post('/my-profile/avatar', [UserProfileController::class, 'updateAvatar'])->name('my-profile.avatar');
});

