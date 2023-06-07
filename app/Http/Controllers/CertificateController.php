<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Internship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
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
        $certificate = Certificate::create([
            'internship_id' => $request->internship_id
        ]);

        return response()->json(compact('certificate'), 201);

        // $internship = $certificate->internship();
        // $student = $internship->student;
        // $studentEmail = $student->user()->email;

        // $department = $student->department;
        // $hod = $department->headOfDepartment;
        // $hodEmail = $hod->user()->email;

        // Mail::to($studentEmail, $hodEmail);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): \Illuminate\Http\Response
    {
        $internship = Internship::findOrFail($id);
        $certificate = $internship->certificate;
        $student = $internship->student;
        $user = $student->user;
        $company = $internship->supervisor->company;
        $supervisor = $internship->supervisor;

        $pdf = Pdf::loadView('pdf.certificate',
            [
                'firstName' => $user->first_name,
                'lastName' => $user->last_name,
                'supervisorFirstName' => $supervisor->user->first_name,
                'supervisorLastName' => $supervisor->user->last_name,
                'birthDate' => $student->date_of_birth,
                'birthPlace'=> $student->place_of_birth,
                'title' => $internship->title,
                'department' => $student->department->name,
                'speciality' => $student->speciality->name,
                'startDate' => $internship->start_date,
                'endDate' => $internship->end_date,
                'date' => date('Y-m-d', strtotime($certificate->created_at)),
                'company' => $company->name,
                'address' => $company->address
            ]
        )->setPaper('a4','landscape');
        return $pdf->download('certificate.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Certificate $certificate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Certificate $certificate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Certificate $certificate)
    {
        //
    }

    public function checkCertification(Request $request,string $id)
    { 
        $isCertified = Certificate::where('internship_id',$id)->exists();
    
        return response()->json(compact('isCertified'), 200);
    }
}
