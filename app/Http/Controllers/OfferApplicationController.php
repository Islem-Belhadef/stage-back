<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use App\Models\OfferApplication;
use App\Models\Supervisor;
use App\Models\Student;
use App\Models\Offer;
use App\Models\HeadOfDepartment;
use Illuminate\Http\Request;

class OfferApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {

        $role = $request->user()['role'];
        $id = $request->user()['id'];
        
        switch ($role) {
            case 0 : {
                $student_id = $request->user()->student->id;
                $applications = OfferApplication::with('offer.supervisor.company')->where('student_id', $student_id)->get();
                return response()->json(compact('applications'));
              

            }
            case 1 : {
                $applications = OfferApplication::with('student.user','offer')->whereIn('status',[0,1,2])->get();
                $hodApplications = [];
                foreach($applications as $application) {
                    $student_id = $application->student_id;
                   // $department_id = Student::find($student_id)->department_id;
                    $department_id = Student::find($student_id)->first(['department_id'])->department_id;

                   // $hod = $user->hod();
                    //$hod_dep_id = HeadOfDepartment::where('user_id',$id)->department_id;
                    $hod_dep_id = HeadOfDepartment::where('user_id',$id)->first(['department_id'])->department_id;

                    if ($department_id == $hod_dep_id) {
                        $hodApplications[] = $application;
                    }
                }
                return response()->json(compact('hodApplications'));
               

            }
            case 2 : {
                
                 $applications = OfferApplication::with('student.user','offer')->whereIn('status',[1,3,4])->get();
                 $supervisor_applications = [];
                 foreach($applications as $application){
                      $offer_id = $application->offer_id;
                      $offer_supervisor = Offer::where('id',$offer_id)->first(['supervisor_id'])->supervisor_id;
                      $supervisor_id = Supervisor::where('user_id',$id)->first(['id'])->id;

                      if ($offer_supervisor == $supervisor_id) {
                        $supervisor_applications[] = $application;

                    }
                                   
                }
       

                return response()->json(compact('supervisor_applications'));
                 
            }
        }
        return response()->json(['message' => "can't fetch any applications"]);

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
        // get student_id from authenticated request 
        $student_id = $request->user()->student->id;
        
        $application = OfferApplication::create([
            'offer_id' => $request->offer_id,
            'student_id' => $student_id,
            'status' => 0,
            'rejection_motive' => null,
        ]);

        return response()->json(compact('application'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(OfferApplication $offerApplication, string $student_id, string $offer_id)
    {
        $application = OfferApplication::where('student_id',$student_id)->where('offer_id',$offer_id)->first();
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
