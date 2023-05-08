<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Supervisor;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $supervisor_id): \Illuminate\Http\JsonResponse
    {
        if ($supervisor_id) {
            $supervisor = Supervisor::findOrFail($supervisor_id);
            $offers = $supervisor->offers();
            return response()->json(['offers' => $offers]);
        }

        $offers = Offer::all()->sortDesc();
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
            'internship_supervisor_id' => 'required',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date',
            'duration' => 'required|integer',
            'available_spots' => 'required',
            'title' => 'required|min:10|max:200',
            'description' => 'required|min:10|max:3000',
        ]);

        $offer = Offer::create([
            'internship_supervisor_id' => $request->internship_supervisor_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'duration' => $request->duration,
            'level' => $request->level,
            'available_spots' => $request->available_spots,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json(['message' => 'Offer created successfully', 'offer' => $offer]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        $offer = Offer::findOrFail($id);
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
}
