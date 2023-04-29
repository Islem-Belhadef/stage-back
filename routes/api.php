<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DemandController;
use App\Http\Controllers\OfferApplicationController;
use App\Http\Controllers\OfferController;
use App\Models\Evaluation;
use App\Models\Offer;
use App\Models\OfferApplication;
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
Route::prefix('/auth')->group(function () {
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/email', [AuthController::class, 'verifyEmail']);
    Route::post('/password/forgot', [AuthController::class, 'sendPasswordResetLink']);
    Route::post('/password/reset', [AuthController::class, 'resetPassword']);
});

// Internship demands routes
Route::prefix('/demands')->middleware('auth:sanctum')->group(function () {
    Route::post('/new', [DemandController::class, 'store'])->middleware('auth:sanctum');
    Route::post('/update', [DemandController::class, 'update']);
    Route::delete('/destroy', [DemandController::class, 'destroy']);
});

// Offer applications routes
Route::prefix('/applications')->middleware('auth:sanctum')->group(function () {
    Route::post('/new', [OfferApplicationController::class, 'store']);
    Route::post('/update', [OfferApplicationController::class, 'update']);
    Route::delete('/destroy', [OfferApplicationController::class, 'destroy']);
});

// Offers routes
Route::prefix('/offers')->group(function () {
    Route::get('/', [OfferController::class, 'index']);
    Route::get('/{id}', [OfferController::class, 'show']);
    Route::post('/new', [OfferController::class, 'store'])->middleware('auth:sanctum');
    Route::post('/update', [OfferController::class, 'update'])->middleware('auth:sanctum');
    Route::post('/destroy', [OfferController::class, 'destroy'])->middleware('auth:sanctum');
});

// Internship routes
Route::prefix('/internships')->group(function(){
   Route::post('/evaluate', [Evaluation::class, 'store']);
   Route::post('/presence', [Presence::class, 'store']);
});
