<?php

namespace App\Http\Controllers;

use App\Models\Cruise;
use Illuminate\Http\Request;

class CruiseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.publicView.cruise.cruiseList');
    }


    public function search(Request $request)
    {
        $destination = $request->destination;
        $departureDate = $request->departure_date;
        $passengers = $request->passengers;

        return view('pages.publicView.cruise.cruiseList', compact(
            'destination',
            'departureDate',
            'passengers'
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
    public function show(Cruise $cruise)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cruise $cruise)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cruise $cruise)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cruise $cruise)
    {
        //
    }
}
