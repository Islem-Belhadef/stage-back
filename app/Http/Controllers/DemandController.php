<?php

namespace App\Http\Controllers;

use App\Models\Demand;
use App\Models\Internship;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class DemandController extends Controller
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
     * @throws ValidationException
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {

        $this->validate($request, [
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date',
            'duration' => 'required|integer',
            'supervisor_email' => 'required|email',
            'title' => 'required|min:10|max:200',
        ]);

        $demand = Demand::create([
            'student_id' => $request->student_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'duration' => $request->duration,
            'supervisor_email' => $request->supervisor_email,
            'status' => 0,
            'rejection_motive' => null,
            'title' => $request->title,
        ]);


        if (!User::where('email', $request->supervisor_email)->exists()) {
            $password = Str::random(12);

            $user = User::create([
                'email' => $demand->supervisor_email,
                'password' => bcrypt($password),
                'first_name' => '',
                'last_name' => '',
                'role' => 2
            ]);

            $supervisor = Supervisor::create([
                'user_id' => $user->id,
                'company_id' => $user->null,
            ]);

//          Send email with account information to the supervisor

            return response()->json(["message" => "no user found", "demand" => $demand, "user" => $user, "supervisor" => $supervisor, "password" => $password], 201);
        }


        $user = User::where('email', $request->supervisor_email)->first();

        return response()->json(["message" => "user exists", "demand" => $demand, "user" => $user], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Demand $demand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Demand $demand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Demand $internshipDemand): \Illuminate\Http\JsonResponse
    {
        $demand = Demand::findOrFail($internshipDemand->id);

        // status:2,4 => rejected
        if ($request->status == 2 or $request->status == 4) {
            $demand->rejection_motive = $request->rejection_motive;
            $demand->status = $request->status;
            $demand->save();
            return response()->json(['demand' => $demand]);
        }

        $demand->status = $request->status;
        $demand->save();

        // status:3 => accepted by HOD and supervisor
        if ($request->status == 3) {
            $internship = Internship::create([
                'student_id' => $request->student_id,
                'supervisor_id' => $request->supervisor_id,
                'start_date' => $internshipDemand->start_date,
                'end_date' => $internshipDemand->end_date,
                'duration' => $internshipDemand->duration,
                'title' => $internshipDemand->title
            ]);
            return response()->json(['demand' => $demand, 'internship' => $internship]);
        }

        return response()->json(['demand' => $demand]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Demand $internshipDemand): \Illuminate\Http\JsonResponse
    {
        if (!$internshipDemand->status == 0 or !$internshipDemand->status == 2 or !$internshipDemand->status == 4) {
            return response()->json(['message' => "Internship demand has already been accepted, you can't remove it"]);
        }

        $demand = Demand::find($internshipDemand->id);
        $demand->delete();

        return response()->json(['message' => "Demand deleted"]);
    }
}
