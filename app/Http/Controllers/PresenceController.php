<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use App\Models\Presence;
use App\Models\Offer;
use Illuminate\Http\Request;

class PresenceController extends Controller
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
    // public function store(Request $request, Internship $internship)
    // {
    //     $latest_Presence = Presence::latest();
    //     $presence = Presence::create([
    //         'internship_id' => $internship->id,
    //         'presence' => $request->presence,
    //     ]);
    //     return response()->json(compact('presence'), 201);
    // }

    public function store(Request $request)
    {
        $latest_Presence = Presence::where('internship_id',$request->internship_id)->latest()->first();
        // check if there is a presence for the same day
        if($latest_Presence){
            $latest_day = date('md', strtotime($latest_Presence->created_at));
            $new_day = date('md', strtotime($request->date));

                if($latest_day == $new_day){
                    return response()->json(['message' => 'exists']);
                }
                    
                    $presence = Presence::create([
                        'internship_id' => $request->internship_id,
                        'presence' => $request->presence,
                    ]);
                    return response()->json(compact('presence'), 201);
                


        }

        $presence = Presence::create([
            'internship_id' => $request->internship_id,
            'presence' => $request->presence,
        ]);
        return response()->json(compact('presence'), 201);
        // return response()->json(['message' => 'not equals'],403);

    }

    /**
     * Display the specified resource.
     */
    public function show(Presence $presence)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Presence $presence)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Presence $presence)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Presence $presence)
    {
        //
    }
}
