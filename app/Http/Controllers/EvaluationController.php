<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Internship;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class EvaluationController extends Controller
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
        $evaluation = Evaluation::create([
            'internship_id' =>$request->internship_id,
            'discipline' => $request->discipline,
            'aptitude' => $request->aptitude,
            'initiative' => $request->initiative,
            'innovation' => $request->innovation,
            'acquired_knowledge' => $request->acquired_knowledge
        ]);

        return response()->json(compact('evaluation'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $internship = Internship::findOrFail($id);
        $evaluation = $internship->evaluation;
        $student = $internship->student;
        $user = $student->user;
        $company = $internship->supervisor->company;
        $supervisor = $internship->supervisor;

        $pdf = Pdf::loadView('pdf.evaluation',
            [
                'firstName' => $user->first_name,
                'lastName' => $user->last_name,
                'supervisorFirstName' => $supervisor->user->first_name,
                'supervisorLastName' => $supervisor->user->last_name,
                'birthDate' => $student->date_of_birth,
                'birthPlace'=> $student->place_of_birth,
                'title' => $internship->title,
                'speciality' => $student->speciality->name,
                'level' => $student->level,
                'semester' => $student->semester,
                'duration' => $internship->duration,
                'startDate' => $internship->start_date,
                'endDate' => $internship->end_date,
                'date' => $evaluation->created_at,
                'company' => $company->name,
                'address' => $company->address,
                'discipline' => $evaluation->discipline,
                'aptitude' => $evaluation->aptitude,
                'initiative' => $evaluation->initiative,
                'innovation' => $evaluation->innovation,
                'acquiredKnowledge' => $evaluation->acquired_knowledge,
                'globalAppreciation' => $evaluation->global_appreciation,
                'total' => ($evaluation->discipline + $evaluation->aptitude + $evaluation->initiative + $evaluation->innovation + $evaluation->acquired_knowledge)
            ]
        );
        return $pdf->download('evaluation.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evaluation $evaluation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evaluation $evaluation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evaluation $evaluation)
    {
        //
    }
}
