<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DemandController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\InternshipController;
use App\Http\Controllers\OfferApplicationController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\PresenceController;
use App\Models\Certificate;
use App\Models\Evaluation;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth routes
Route::prefix('/auth')->controller(AuthController::class)->group(function () {
    Route::post('/signup', 'signup');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout');
    Route::post('/email', 'verifyEmail');
    Route::post('/password/forgot', 'sendPasswordResetLink');
    Route::post('/password/reset', 'resetPassword');
});

// Internship demands routes
Route::prefix('/demands')->controller(DemandController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index');
    Route::get('/{}', 'show');
    Route::post('/new', 'store');
    Route::put('/update', 'update');
    Route::delete('/destroy/{id}', 'destroy');
});

// Offer applications routes
Route::prefix('/applications')->controller(OfferApplicationController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index');
    Route::get('/{}', 'show');
    Route::post('/new','store');
    Route::put('/update','update');
    Route::delete('/destroy/{id}','destroy');
});

// Offers routes
Route::prefix('/offers')->controller(OfferController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::post('/new', 'store')->middleware('auth:sanctum');
    Route::put('/update', 'update')->middleware('auth:sanctum');
    Route::delete('/destroy/{id}', 'destroy')->middleware('auth:sanctum');
});

// Internship routes
Route::prefix('/internships')->group(function () {
    Route::get('/', [InternshipController::class,'index']);
    Route::get('/{id}', [InternshipController::class,'show']);
    Route::post('/evaluate', [EvaluationController::class, 'store']);
    Route::post('/presence', [PresenceController::class, 'store']);
    Route::post('/certificate', [CertificateController::class, 'store']);
});

// SuperAdmin routes
Route::prefix('/accounts')->controller(AccountsController::class)->middleware('auth:sanctum')->group(function(){
    Route::post('/new', 'store');
    Route::put('/update', 'update');
    Route::delete('/destroy/{id}', 'destroy');
});

// Extra routes
Route::post('/contact', [ContactController::class, 'store']);
