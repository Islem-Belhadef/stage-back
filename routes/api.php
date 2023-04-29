<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DemandController;
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
Route::prefix('/demand')->middleware('auth:sanctum')->group(function () {
    Route::post('/new', [DemandController::class, 'store'])->middleware('auth:sanctum');
    Route::post('/update', [DemandController::class, 'update']);
    Route::delete('/destroy', [DemandController::class, 'destroy']);
});

// Offer applications routes
Route::prefix('/application')->middleware('auth:sanctum')->group(function () {
    Route::post('/new', [OfferApplication::class, 'store']);
    Route::post('/update', [OfferApplication::class, 'update']);
    Route::delete('/destroy', [OfferApplication::class, 'destroy']);
});

// Offers routes
Route::prefix('/offer')->middleware('auth:sanctum')->group(function () {
    Route::post('/new', [Offer::class, 'store']);
    Route::post('/update', [Offer::class, 'update']);
    Route::post('/destroy', [Offer::class, 'destroy']);
});

Route::prefix('/internship')->group(function(){
   Route::post('/evaluate', [Evaluation::class, 'store']);
   Route::post('/presence', [Presence::class, 'store']);
});
