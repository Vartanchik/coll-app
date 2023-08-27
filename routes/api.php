<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ContributorController;
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

Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group( function () {
    Route::apiResource('/collections', CollectionController::class);
    Route::apiResource('/contributors', ContributorController::class);
    Route::delete('/collections/{id}', [CollectionController::class, 'destroy']);
    Route::delete('/contributors/{id}', [ContributorController::class, 'destroy']);
});
