<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\OfferApplication;
use App\Models\Company;
use App\Models\Supervisor;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(): \Illuminate\Http\JsonResponse
    {
        $offers = Offer::with('supervisor.company')->orderByDesc('created_at')->get();

        return response()->json(['offers' => $offers]);
    }


    public function supervisorOffers(Request $request): \Illuminate\Http\JsonResponse
    {
        $supervisor_id = $request->user()->supervisor->id;
        $offers = $request->user()->supervisor->offers;
        return response()->json(['offers' => $offers]);
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
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date',
            'available_spots' => 'required',
            'title' => 'required|min:10|max:200',
            'description' => 'required|min:10|max:3000',
            'level' => 'required|string'
        ]);
        // get supervisor from the request
        $supervisor_id = $request->user()->supervisor->id;

        $offer = Offer::create([
            'supervisor_id' => $supervisor_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'duration' => (round((strtotime($request->end_date) - strtotime($request->start_date)) / 86400)),
            'level' => $request->level,
            'available_spots' => $request->available_spots,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json(['message' => 'Offer created successfully', 'offer' => $offer], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        $offer = Offer::with('supervisor.company')->findOrFail($id);
        return response()->json(['offer' => $offer]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offer $offer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $offer = Offer::findOrFail($id);
        $offer->available_spots--;
        $offer->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $offer = Offer::findOrFail($id);
        $offer->delete();
        $message = 'Offer deleted successfully';
        return response()->json(compact('message'));
    }

    

    public function checkApplication(Request $request,string $id)
    { 
        $student_id = $request->user()->student->id;
        $isApplied = OfferApplication::where('student_id',$student_id)->where('offer_id',$id)->exists();
    
        return response()->json(compact('isApplied'), 200);
    }
}
