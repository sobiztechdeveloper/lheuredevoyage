<?php

use App\Http\Controllers\Api\DestinationSearchController;
use Illuminate\Support\Facades\Route;

Route::get('/destinations/search', DestinationSearchController::class)->name('api.destinations.search');
