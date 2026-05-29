<?php

namespace App\Http\Controllers;

use App\Models\UserBookingHistory;
use Illuminate\Http\Request;

class UserBookingHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return view('pages.publicUserView.myBookingHistory');
    }

    /**
     * Show the form for creating a new resource.
     */
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
    public function show(UserBookingHistory $userBookinHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserBookingHistory $userBookinHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserBookingHistory $userBookinHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserBookingHistory $userBookinHistory)
    {
        //
    }
}
