<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DemandController;
use App\Models\Offer;
use App\Models\OfferApplication;
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

Route::prefix('/auth')->group(function () {
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/email', [AuthController::class, 'verifyEmail']);
});

Route::prefix('/demand')->group(function () {
    Route::post('/new', [DemandController::class, 'store']);
    Route::post('/update', [DemandController::class, 'update']);
    Route::delete('/destroy', [DemandController::class, 'destroy']);
});

Route::prefix('/application')->group(function () {
    Route::post('/new', [OfferApplication::class, 'store']);
    Route::post('/update', [OfferApplication::class, 'update']);
    Route::delete('/destroy', [OfferApplication::class, 'destroy']);
});

Route::prefix('/offer')->group(function () {
    Route::post('/new', [Offer::class, 'store']);
    Route::post('/destroy', [Offer::class, 'destroy']);
});
