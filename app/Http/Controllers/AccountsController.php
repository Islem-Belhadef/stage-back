<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupRequest;
use App\Mail\AccountCreated;
use App\Mail\ConfirmEmail;
use App\Models\HeadOfDepartment;
use App\Models\Student;
use App\Models\SuperAdministrator;
use App\Models\Supervisor;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $password = Str::random(12);
        $type = 'super administrator';

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($password),
            'role' => $request->role
        ]);

        if ($request->role == 0) {

            $type = 'student';
            Student::create([
                'department_id' => $request->department_id,
                'speciality_id' => $request->speciality_id,
                'semester' => $request->semester,
                'academic_year' => $request->academic_year,
                'date_of_birth' => $request->date_of_birth,
                'user_id' => $user->id
            ]);

        } else if ($request->role == 1) {

            $type = 'head of department';
            HeadOfDepartment::create([
                'department_id' => $request->department_id,
                'user_id' => $user->id
            ]);

        } else if ($request->role == 2) {

            $type = 'supervisor';
            Supervisor::create([
                'company_id' => $request->company_id,
                'user_id' => $user->id
            ]);

        } else {

            SuperAdministrator::create([
                'user_id' => $user->id
            ]);

        }

        // Random 6 characters code to verify email address
        $code = '';

        for ($i = 0; $i < 6; $i++) {
            $code = $code . strval(rand(0, 9));
        }

        $verification = VerificationCode::create([
            'user_id' => $user->id,
            'code' => $code
        ]);

        Mail::to($request->email)->send(new AccountCreated($type, $request->email, $password));
        Mail::to($request->email)->send(new ConfirmEmail($type, $code));

        return response()->json(['message' => 'User account created and email sent successfully', 'user' => $user, 'type' => $type , 'verification' => $verification], 201);
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
         $user = User::findOrFail($id);
        // $user->first_name = $request->first_name;
        // $user->last_name = $request->last_name;
        // $user->email = $request->email;
        // $user->password = bcrypt($request->password);
        // $user->save();
        $user->update([
            "first_name" => ($request->first_name)?$request->first_name:$user->first_name,
            "last_name" => ($request->last_name)?$request->last_name:$user->last_name,
            "email" => ($request->email)?$request->email:$user->email,
           // "password" => bcrypt($request->password)
          

        ]);

       $message = 'User information updated successfully';

        if ($request->role == 0) {
            // $student = $user->student;
            // $student->department_id = $request->department_id;
            // $student->speciality_id = $request->speciality_id;
            // $student->semester = $request->semester;
            // $student->academic_year = $request->academic_year;
            // $student->date_of_birth = $request->date_of_birth;
            // $student->save();
           // $student = Student::where("user_id",$id)->first();
           $student = $user->student;
            $student->update([
                "department_id" =>($request->department_id)?$request->department_id:$student->department_id,
                "speciality_id" =>($request->speciality_id)?$request->speciality_id:$student->speciality_id,
                "semester" =>  ($request->semester)?$request->semester:$student->semester,
                "academic_year" => ($request->academic_year)?$request->academic_year:$student->academic_year,
                "date_of_birth" => ($request->date_of_birth)?$request->date_of_birth:$student->date_of_birth,
            ]);

            return response()->json(compact('message', 'user'));
        } else if ($request->role == 1) {
            $hod = $user->hod;
            // $hod->department_id = $request->department_id;
            // $hod->save();
            $hod->update([
                "department_id"=>($request->department_id)?$request->department_id:$hod->department_id,
            ]);
          

            return response()->json(compact('message', 'user', 'hod'));
        } else if ($request->role == 2) {
            $supervisor = $user->supervisor;
            // $supervisor->company_id = $request->company_id;
            // $supervisor->save();
            $supervisor->update([
                "company_id" =>($request->company_id)?$request->company_id:$supervisor->company_id,
            ]);

            return response()->json(compact('message', 'user', 'supervisor'));
        }

       return response()->json(compact('message', 'user'));
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): \Illuminate\Http\JsonResponse
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
