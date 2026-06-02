<?php

namespace App\Http\Controllers;

use App\Models\rentalCar;
use Illuminate\Http\Request;

class RentalCarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.publicView.rentalCar.rentalCarList');
    }

    public function search(Request $request)
    {
        $pickupLocation = $request->pickup_location;
        $pickupDate = $request->pickup_date;
        $returnDate = $request->return_date;

        return view('pages.publicView.rentalcar.rentalCarList', compact(
            'pickupLocation',
            'pickupDate',
            'returnDate'
        ));
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
    public function show(rentalCar $rentalCar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(rentalCar $rentalCar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, rentalCar $rentalCar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(rentalCar $rentalCar)
    {
        //
    }
}
