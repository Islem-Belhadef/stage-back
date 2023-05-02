<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DemandController;
use App\Http\Controllers\OfferApplicationController;
use App\Http\Controllers\OfferController;
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
    Route::post('/email', 'verifyEmail');
    Route::post('/password/forgot', 'sendPasswordResetLink');
    Route::post('/password/reset', 'resetPassword');
});

// Internship demands routes
Route::prefix('/demands')->controller(DemandController::class)->middleware('auth:sanctum')->group(function () {
    Route::post('/new', 'store');
    Route::post('/update', 'update');
    Route::delete('/destroy', 'destroy');
});

// Offer applications routes
Route::prefix('/applications')->controller(OfferApplicationController::class)->middleware('auth:sanctum')->group(function () {
    Route::post('/new','store');
    Route::post('/update','update');
    Route::delete('/destroy','destroy');
});

// Offers routes
Route::prefix('/offers')->controller(OfferController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::post('/new', 'store')->middleware('auth:sanctum');
    Route::post('/update', 'update')->middleware('auth:sanctum');
    Route::post('/destroy', 'destroy')->middleware('auth:sanctum');
});

// Internship routes
Route::prefix('/internships')->group(function () {
    Route::post('/evaluate', [Evaluation::class, 'store']);
    Route::post('/presence', [Presence::class, 'store']);
    Route::post('/certificate', [Certificate::class, 'store']);
});
