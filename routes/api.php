<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TokenController;
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
//
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('token')->group(function () {
        Route::post('/create', [TokenController::class, 'create']);
        Route::delete('/delete/{id}', [TokenController::class, 'destroy']);
    });
});

Route::middleware(['auth:token','request_logger'])->group(function () {
    Route::get('/me', function (Request $request) {
        return $request->user();
    });
});


