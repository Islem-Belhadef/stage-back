<?php

namespace App\Http\Controllers;

use App\Models\Demand;
use App\Models\Offer;
use App\Models\OfferApplication;
use Illuminate\Http\Request;

class OfferApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request, Offer $offer)
    {
        $application = OfferApplication::create([
            'offer_id' => $offer->id,
            'student_id' => $request->student_id,
            'status' => 0,
            'rejection_motive' => null,
            'date' => $request->date,
            'title' => $request->title,
        ]);

        return response(['application' => $application], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(OfferApplication $offerApplication)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OfferApplication $offerApplication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OfferApplication $offerApplication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OfferApplication $offerApplication)
    {
        //
    }
}
