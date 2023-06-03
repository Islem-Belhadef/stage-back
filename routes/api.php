<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DemandController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\InternshipController;
use App\Http\Controllers\OfferApplicationController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\PresenceController;
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
    Route::post('/register', 'store');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
    Route::post('/email', 'verifyEmail');
    Route::post('/password/forgot', 'sendPasswordResetLink');
    Route::post('/password/reset', 'resetPassword');
    Route::get('/profile', 'getProfile')->middleware('auth:sanctum', 'verified');
});

// Internship demands routes
Route::prefix('/demands')->controller(DemandController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::post('/new', 'store')->middleware('verified');
    Route::put('/update/{id}', 'update')->middleware('verified');
    Route::delete('/destroy/{id}', 'destroy')->middleware('verified');
});

// Offer applications routes
Route::prefix('/applications')->controller(OfferApplicationController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index');
    Route::get('/{student_id}/{offer_id}', 'show');
    Route::post('/new', 'store');
    Route::put('/update/{id}', 'update');
    Route::delete('/destroy/{id}', 'destroy');
});

// Offers routes
Route::prefix('/offers')->controller(OfferController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::get('/supervisor/offers/', 'supervisorOffers')->middleware('auth:sanctum', 'verified');
    Route::post('/new', 'store')->middleware('auth:sanctum', 'verified');
    Route::put('/update/{id}', 'update')->middleware('auth:sanctum', 'verified');
    Route::delete('/destroy/{id}', 'destroy')->middleware('auth:sanctum', 'verified');
});

// Internship routes
Route::prefix('/internships')->group(function () {
    Route::get('/', [InternshipController::class, 'index']);
    Route::get('/{id}', [InternshipController::class, 'show']);
    Route::post('/evaluate', [EvaluationController::class, 'store'])->middleware('auth:sanctum', 'verified');
    Route::post('/presence', [PresenceController::class, 'store'])->middleware('auth:sanctum', 'verified');
    Route::post('/certificate', [CertificateController::class, 'store'])->middleware('auth:sanctum', 'verified');
    Route::get('/certificate/{id}', [CertificateController::class, 'show'])->middleware('auth:sanctum'/*, 'verified'*/);
});

// Super Administrator routes
Route::prefix('/accounts')->controller(AccountsController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index');
    Route::post('/new', 'store')->middleware('verified');
    Route::put('/update/{id}', 'update')->middleware('verified');
    Route::delete('/destroy/{id}', 'destroy')->middleware('verified');
});



// Extra routes
Route::post('/contact', [ContactController::class, 'store']);
Route::get('/questions', [ContactController::class, 'index']);
Route::controller(Controller::class)->group(function () {
    Route::get('/departments', 'department');
    Route::get('/specialities', 'speciality');
    Route::get('/companies', 'companies');
});
