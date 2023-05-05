<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use App\Models\OfferApplication;
use App\Models\Supervisor;
use Illuminate\Http\Request;

class OfferApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $supervisor_id)
    {
        if ($supervisor_id) {
            $supervisor = Supervisor::findOrFail($supervisor_id);
            $offers = $supervisor->offers();
            $applications = [];
            for ($i = 0; $i < count($offers); $i++) {
                $application = OfferApplication::where('offer_id', $offers[$i]['id'])->first();
                $applications[] = $application;
            }
            return response()->json(compact('applications'));
        }

        $applications = OfferApplication::where('status', 0)->get();
        return response()->json(compact('applications'));
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
        $application = OfferApplication::create([
            'offer_id' => $request->offer_id,
            'student_id' => $request->student_id,
            'status' => 0,
            'rejection_motive' => null,
            'date' => $request->date,
        ]);

        return response()->json(compact('application'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(OfferApplication $offerApplication, string $id)
    {
        $application = OfferApplication::findOrFail($id);
        return response()->json(compact('application'));
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
    public function update(Request $request, string $id): \Illuminate\Http\JsonResponse
    {
        $application = OfferApplication::findOrFail($id);

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
                'start_date' => $application->start_date,
                'end_date' => $application->end_date,
                'duration' => $application->duration,
                'title' => $application->title
            ]);
            return response()->json(compact('application', 'internship'));
        }

        return response()->json(compact('application'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): \Illuminate\Http\JsonResponse
    {
        $application = OfferApplication::findOrFail($id);

        if ($application->status == 1 or $application->status == 3) {
            return response()->json(['message' => "Application has already been accepted, you can't remove it"]);
        }

        $application->delete();

        return response()->json(['message' => "Application deleted"]);
    }
}
