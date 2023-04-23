<?php

namespace App\Http\Controllers;

use App\Models\Demand;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
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
    public function store(Request $request): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
    {

//        $this->validate($request, [
//            'start_date' => 'required|date|after_or_equal:today',
//            'end_date' => 'required|date',
//            'duration' => 'required|integer',
//            'supervisor_email' => 'required|email',
//            'title' => 'required|min:12|max:200',
//        ]);

        $demand = Demand::create([
            'student_id' => $request->student_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'duration' => $request->duration,
            'supervisor_email' => $request->supervisor_email,
            'status' => 0,
            'rejection_motive' => null,
            'date' => $request->date,
            'title' => $request->title,
        ]);

        $user = User::find('email', $request->supervisor_email);

        $pwd = Str::random(12);

        if (!$user) {
            $usr = User::create([
                'email' => $demand->supervisor_email,
                'password' => bcrypt($pwd),
                'first_name' => null,
                'last_name' => null,
                'role' => 2
            ]);

            return response(["demand" => $demand, "user" => $usr], 200);
        }

//        Send email with account information to the supervisorF

        return response(["demand" => $demand, "user" => $user], 200);
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
    public function update(Request $request, Demand $demand)
    {
        $dem = Demand::find($demand->id);
        $dem->status = $request->status;
        $dem->save();

        return response($dem, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Demand $demand)
    {
        $dem = Demand::find($demand->id);
        $dem->delete();

        return response("Demand deleted", 200);
    }
}
