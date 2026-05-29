<?php

namespace App\Http\Controllers;

use App\Models\UserMyBooking;
use Illuminate\Http\Request;

class UserMyBookingController extends Controller
{

    public function index()
    {
        // Gate::authorize('viewAny', Booking::class);

        // $bookings = Booking::query()
        //     ->where('user_id', auth()->id())
        //     ->with('bookable')
        //     ->latest()
        //     ->get();

        return view('pages.publicUserView.myBooking');
    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UserMyBooking $userMyBooking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserMyBooking $userMyBooking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserMyBooking $userMyBooking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserMyBooking $userMyBooking)
    {
        //
    }
}
