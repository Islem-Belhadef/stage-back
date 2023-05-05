<?php

namespace App\Http\Controllers;

use App\Mail\AccountCreated;
use App\Models\Demand;
use App\Models\Internship;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class DemandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $supervisor_id)
    {
        if ($supervisor_id) {
            $supervisor = Supervisor::findOrFail($supervisor_id);
            $email = User::findOrFail($supervisor->id)->first()->email;
            $demands = Demand::where('supervisor_email', $email, 'status', 1)->get();
            return response()->json(compact('demands'));
        }

        $demands = Demand::where('status', 0)->get();
        return response()->json(compact('demands'));
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

            Mail::to($demand->supervisor_email)->send(new AccountCreated('supervisor', $demand->supervisor_email, $password));

            return response()->json(["message" => "account created and email sent successfully", "demand" => $demand, "user" => $user, "supervisor" => $supervisor, "password" => $password], 201);
        }

        $user = User::where('email', $request->supervisor_email)->first();

        return response()->json(["message" => "supervisor account exists demand created successfully", "demand" => $demand, "user" => $user], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $demand = Demand::findOrFail($id);
        return response()->json(compact('demand'));
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
    public function update(Request $request, string $id): \Illuminate\Http\JsonResponse
    {
        $demand = Demand::findOrFail($id);

        // status:2,4 => rejected
        if ($request->status == 2 or $request->status == 4) {
            $demand->rejection_motive = $request->rejection_motive;
            $demand->status = $request->status;
            $demand->save();
            return response()->json(compact('demand'));
        }

        $demand->status = $request->status;
        $demand->save();

        // status:3 => accepted by HOD and supervisor
        if ($request->status == 3) {
            $internship = Internship::create([
                'student_id' => $demand->student_id,
                'supervisor_id' => $request->supervisor_id,
                'start_date' => $demand->start_date,
                'end_date' => $demand->end_date,
                'duration' => $demand->duration,
                'title' => $demand->title
            ]);
            return response()->json(compact('demand', 'internship'));
        }

        return response()->json(compact('demand'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): \Illuminate\Http\JsonResponse
    {
        $demand = Demand::findOrFail($id);

        if ($demand->status == 1 or $demand->status == 3) {
            return response()->json(['message' => "Internship demand has already been accepted, you can't remove it"]);
        }

        $demand->delete();

        return response()->json(['message' => "Demand deleted"]);
    }
}
