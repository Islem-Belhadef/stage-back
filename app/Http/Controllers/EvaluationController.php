<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Internship;
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
    public function store(Request $request, Internship $internship)
    {
        $evaluation = Evaluation::create([
            'internship_id' => $internship->id,
            'discipline' => $request->discipline,
            'aptitude' => $request->aptitude,
            'initiative' => $request->initiative,
            'innovation' => $request->innovation,
            'acquired_knowledge' => $request->acquiredKnowledge
        ]);

        return response()->json(['evaluation' => $evaluation], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Evaluation $evaluation)
    {
        //
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
