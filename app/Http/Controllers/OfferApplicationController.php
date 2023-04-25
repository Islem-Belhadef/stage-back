<?php

namespace App\Http\Controllers;

use App\Models\Internship;
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
    public function store(Request $request, Offer $offer): \Illuminate\Http\JsonResponse
    {
        $application = OfferApplication::create([
            'offer_id' => $offer->id,
            'student_id' => $request->student_id,
            'status' => 0,
            'rejection_motive' => null,
            'date' => $request->date,
        ]);

        return response()->json(['application' => $application], 201);
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
    public function update(Request $request, OfferApplication $offerApplication): \Illuminate\Http\JsonResponse
    {
        $application = OfferApplication::find($offerApplication->id);

        // status:2,4 => rejected
        if ($request->status == 2 or $request->status == 4) {
            $application->rejection_motive = $request->rejection_motive;
            $application->status = $request->status;
            $application->save();
            return response()->json(['application' => $application]);
        }

        $application->status = $request->status;
        $application->save();

        // status:3 => accepted by HOD and supervisor
        if ($request->status == 3) {
            $internship = Internship::create([
                'student_id' => $request->student_id,
                'supervisor_id' => $request->supervisor_id,
                'start_date' => $offerApplication->start_date,
                'end_date' => $offerApplication->end_date,
                'duration' => $offerApplication->duration,
                'title' => $offerApplication->title
            ]);
            return response()->json(['application' => $application, 'internship' => $internship]);
        }

        return response()->json(['application' => $application]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OfferApplication $offerApplication): \Illuminate\Http\JsonResponse
    {
        if (!$offerApplication->status == 0 or !$offerApplication->status == 2 or !$offerApplication->status == 4) {
            return response()->json(['message' => "Offer application has already been accepted, you can't remove it"]);
        }

        $application = OfferApplication::find($offerApplication->id);
        $application->delete();

        return response()->json(['message' => "Application deleted"]);
    }
}
