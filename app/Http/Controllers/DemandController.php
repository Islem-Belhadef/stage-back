<?php

namespace App\Http\Controllers;

use App\Mail\AccountCreated;
use App\Mail\ConfirmEmail;
use App\Models\Demand;
use App\Models\Internship;
use App\Models\Supervisor;
use App\Models\Student;
use App\Models\Company;
use App\Models\HeadOfDepartment;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class DemandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {

        $role = $request->user()['role'];
        $id = $request->user()['id'];
        $email = $request->user()['email'];

        switch ($role) {
            case 0: {
                    $student_id = $request->user()->student->id;
                    // $student_id = Student::where('user_id',$id)->first(['id'])->id;
                    $demands = Demand::where('student_id', $student_id)->get();
                    return response()->json(compact('demands'));
                    // return response()->json(['demands for student ' => $demands]);

                }
            case 1: {
                    $demands = Demand::with('student.user','student.speciality')->whereIn('status', [0, 1, 2])->get();
                    //whereIn('status',[0,2,3]) to fetch only the status the hod is concerned about pending accepted or refused by him
                    $hodDemands = [];
                    foreach ($demands as $demand) {
                        $student_id = $demand->student_id;
                        $department_id = Student::find($student_id)->first(['department_id'])->department_id;


                        $hod_dep_id = HeadOfDepartment::where('user_id', $id)->first(['department_id'])->department_id;

                        if ($department_id == $hod_dep_id) {
                            $hodDemands[] = $demand;
                        }
                    }
                    return response()->json(compact('hodDemands'));
                }
            case 2: {
                $supervisor_id = $request->user()->supervisor->id;
                    $demands = Demand::with('student.user','student.speciality')->where('supervisor_id', $supervisor_id)->whereIn('status', [1, 3, 4])->get();
                    //whereIn('status',[1,3,4]) to fetch only the status the supervisor is concerned about pending accepted or refused by him

                    return response()->json(compact('demands'));
                }
        }
        return response()->json(['message' => "can't fetch any demands"]);
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
            'supervisor_email' => 'required|email',
            'company' => 'required|string',
            'title' => 'required|min:10|max:200',
            'motivational_letter' =>'nullable|string'
        ]);

        $student_id = $request->user()->student->id;


        if (!User::where('email', $request->supervisor_email)->exists()) {

            $demand = Demand::create([
                'student_id' => $student_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'duration' => (round((strtotime($request->end_date)-strtotime($request->start_date))/86400)),
                // 'supervisor_id' => $supervisor->id,
                'supervisor_email' => $request->supervisor_email,
                'company' => $request->company,
                'status' => 0,
                'rejection_motive' => null,
                'title' => $request->title,
                'motivational_letter' => $request->motivational_letter
            ]);


            return response()->json(
                [
                    "message" => "demand created successfully",
                    "demand" => $demand,
                ],
                201
            );
        }

        $user = User::where('email', $request->supervisor_email)->first();
        $supervisor = $user->supervisor;

        $demand = Demand::create([
            'student_id' => $student_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'duration' => (round((strtotime($request->end_date)-strtotime($request->start_date))/86400)),
            'supervisor_id' => $supervisor->id,
            // 'supervisor_email' => $request->supervisor_email,
            'company' => $request->company,
            'status' => 0,
            'rejection_motive' => null,
            'title' => $request->title,
            'motivational_letter' => $request->motivational_letter
        ]);

        return response()->json(
            [
                "message" => "supervisor account exists demand created successfully",
                "demand" => $demand, "user" => $user, "supervisor" => $supervisor
            ],
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): \Illuminate\Http\JsonResponse
    {
        $demand = Demand::findOrFail($id);
        return response()->json(compact('demand'));
    }

    /**
     * Show the form for editing the specified resource.
     * edit demand info
     */
    public function edit(Request $request, string $id)
    {
        //
        $demand = Demand::findOrFail($id);

        if($demand->status == 0){
            
            $demand->update([
                "title" => ($request->title) ? $request->title : $demand->title,
                "supervisor_email" => ($request->supervisor_email) ? $request->supervisor_email : $demand->supervisor_email,
                "company" => ($request->company) ? $request->company : $demand->company,
                "start_date" => ($request->start_date) ? $request->start_date : $demand->start_date,
                "end_date" => ($request->end_date) ? $request->end_date : $demand->end_date,
                "duration" => $request->start_date && $request->end_date
                            ? round((strtotime($request->end_date) - strtotime($request->start_date)) / 86400)
                                : ($request->start_date && !$request->end_date
                                   ? round((strtotime($demand->end_date) - strtotime($request->start_date)) / 86400)
                                     : (!$request->start_date && $request->end_date
                                         ? round((strtotime($request->end_date) - strtotime($demand->start_date)) / 86400)
                                           : $demand->duration)),
            ]);
    
            
            $message = 'Demand information updated successfully';
            return response()->json(compact('message', 'demand'),200);
        }

        $message = 'cannot edit demand';
        return response()->json(compact('message'));


    }

    /**
     * Update the specified resource in storage.
     * change demand status
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

        // if demand accepted by HOD and supervisor doesnt exist create supervisor account 
        if($request->status == 1 && !$demand->supervisor_id){
            $password = Str::random(12);

            $user = User::create([
                'email' => $demand->supervisor_email,
                'password' => bcrypt($password),
                'first_name' => '',
                'last_name' => '',
                'role' => 2
            ]);

            $company = Company::create([
                'name'=> $demand->company,
            ]);

            $supervisor = Supervisor::create([
                'user_id' => $user->id,
                'company_id' =>$company->id,
            ]);

            $demand->supervisor_id = $supervisor->id;
            $demand->save();

            // Random 6 characters code to verify email address
            $code = '';

            for ($i = 0; $i < 6; $i++) {
                $code = $code . strval(rand(0, 9));
            }

            $verification = VerificationCode::create([
                'user_id' => $user->id,
                'code' => $code
            ]);

            Mail::to($demand->supervisor_email)->send(new AccountCreated('supervisor', $demand->supervisor_email, $password));
            Mail::to($demand->supervisor_email)->send(new ConfirmEmail('supervisor', $code));

            return response()->json(
                [
                    "message" => "demand accepted and account created and email sent successfully",
                    "demand" => $demand, 
                    "user" => $user, 
                    "supervisor" => $supervisor,
                    "password" => $password, 
                    "verification" => $verification
                ],
                201
            );
        }

        // status:3 => accepted by HOD and supervisor
        if ($request->status == 3) {
            $internship = Internship::create([
                'student_id' => $demand->student_id,
                'supervisor_id' => $demand->supervisor_id,
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
