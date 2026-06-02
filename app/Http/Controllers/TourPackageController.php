<?php

namespace App\Http\Controllers;

use App\Models\tourPackage;
use Illuminate\Http\Request;

class TourPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.publicView.tourPackage.tourPackageList');
    }

    public function search(Request $request)
    {
        $destination = $request->destination;
        $travelDate = $request->travel_date;
        $travelers = $request->travelers;

        return view('pages.publicView.tourpackage.tourPackageList', compact(
            'destination',
            'travelDate',
            'travelers'
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
    public function show(tourPackage $tourPackage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(tourPackage $tourPackage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, tourPackage $tourPackage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(tourPackage $tourPackage)
    {
        //
    }
}
