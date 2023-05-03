<?php

namespace App\Http\Controllers;

use App\Models\HeadOfDepartment;
use App\Models\Student;
use App\Models\SuperAdministrator;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Http\Request;

class AccountsController extends Controller
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
    public function store(Request $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role
        ]);

        if ($request->role == 0) {

            $student = Student::create([
                'department_id' => $request->department_id,
                'speciality_id' => $request->speciality_id,
                'semester' => $request->semester,
                'academic_year' => $request->academic_year,
                'date_of_birth' => $request->date_of_birth,
                'user_id' => $user->id
            ]);

            return response()->json(['user' => $user, 'student' => $student]);
        } else if ($request->role == 1) {

            $hod = HeadOfDepartment::create([
                'department_id' => $request->department_id,
                'speciality_id' => $request->speciality_id,
                'semester' => $request->semester,
                'academic_year' => $request->academic_year,
                'date_of_birth' => $request->date_of_birth,
                'user_id' => $user->id
            ]);

            return response()->json(['user' => $user, 'hod' => $hod]);

        } else if ($request->role == 2) {

            $supervisor = Supervisor::create([
                'department_id' => $request->department_id,
                'speciality_id' => $request->speciality_id,
                'semester' => $request->semester,
                'academic_year' => $request->academic_year,
                'date_of_birth' => $request->date_of_birth,
                'user_id' => $user->id
            ]);

            return response()->json(['user' => $user, 'student' => $supervisor]);

        }
        $superAdmin = SuperAdministrator::create([
            'department_id' => $request->department_id,
            'speciality_id' => $request->speciality_id,
            'semester' => $request->semester,
            'academic_year' => $request->academic_year,
            'date_of_birth' => $request->date_of_birth,
            'user_id' => $user->id
        ]);

        return response()->json(['user' => $user, 'student' => $superAdmin]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,string $id)
    {
        if ($request->role == 0) {
            $student = Student::findOrFail($id);
        }
        if ($request->role == 1) {
            $hod = HeadOfDepartment::findOrFail($id);
        }
        if ($request->role == 2) {
            $supervisor = Supervisor::findOrFail($id);
        }
    }
}
