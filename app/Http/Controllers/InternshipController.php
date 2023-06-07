<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use App\Models\Offer;
use Illuminate\Http\Request;

class InternshipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index($id): \Illuminate\Http\JsonResponse
    // {
    //     if ($id) {
    //         $internships = Internship::where('supervisor_id', $id)->get();
    //         return response()->json(compact('internships'));
    //     }

    //     $internships = Internship::all();
    //     return response()->json(compact('internships'));
    // }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $id = $request->user()->id;
        $role = $request->user()->role;

        switch ($role) {
            case 0: {
                    $student_id = $request->user()->student->id;

                    $internships = Internship::with('supervisor.user','supervisor.company')->where('student_id', $student_id)->get();
                    return response()->json(compact('internships'));
                }

                case 2: {
                     $supervisor_id = $request->user()->supervisor->id;
                      $internships = Internship::with('student.user','student.department','student.speciality','supervisor.user','supervisor.company')->where('supervisor_id', $supervisor_id)->get();
                    return response()->json(compact('internships'));
                  
                    
                return response()->json(compact('internships'));

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
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): \Illuminate\Http\JsonResponse
    {
        $internship = Internship::findOrFail($id);
        return response()->json(compact('internship'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Internship $internship)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Internship $internship)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Internship $internship)
    {
        //
    }
}
