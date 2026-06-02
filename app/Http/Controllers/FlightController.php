<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function index()
    {
        return view('pages.publicView.flight.flightList');
    }

    public function search(Request $request)
    {
        $from = $request->from_destination;
        $to = $request->to_destination;
        $journeyDate = $request->journey_date;

        return view('pages.publicView.flight.flightList', compact(
            'from',
            'to',
            'journeyDate'
        ));
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(Flight $flight)
    {
        //
    }

    public function edit(Flight $flight)
    {
        //
    }


    public function update(Request $request, Flight $flight)
    {
        //
    }


    public function destroy(Flight $flight)
    {
        //
    }
}
