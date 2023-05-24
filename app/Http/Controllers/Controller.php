<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Department;
use App\Models\Speciality;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function department(): \Illuminate\Http\JsonResponse
    {
        $departments = Department::all();
        return response()->json(compact('departments'));
    }

    public function speciality(): \Illuminate\Http\JsonResponse
    {
        $specialities = Speciality::all();
        return response()->json(compact('specialities'));
    }

    public function companies(): \Illuminate\Http\JsonResponse
    {
        $companies = Company::all();
        return response()->json(compact('companies'));
    }
}
