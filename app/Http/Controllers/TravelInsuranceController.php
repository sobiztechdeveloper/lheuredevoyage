<?php

namespace App\Http\Controllers;

use App\Models\travelInsurance;
use Illuminate\Http\Request;

class TravelInsuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.publicView.travelInsurance.travelInsuranceList');
    }

    public function search(Request $request)
    {
        $destination = $request->destination;
        $travelDate = $request->travel_date;

        return view('pages.publicView.travelinsurance.travelInsuranceList', compact(
            'destination',
            'travelDate'
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
    public function show(travelInsurance $travelInsurance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(travelInsurance $travelInsurance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, travelInsurance $travelInsurance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(travelInsurance $travelInsurance)
    {
        //
    }
}
