<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProfileController;

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

Route::prefix('users')->group(function () {
    Route::post('', [UserController::class, 'create']);
    Route::post('/login', [UserController::class, 'login']);
    Route::post('request-reset', [UserController::class, 'requestReset']);
    Route::post('/reset-password', [UserController::class, 'resetPassword']);
});

Route::middleware('auth.admin')->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('', [UserController::class, 'getAll']);
        Route::get('{id}', [UserController::class, 'get']);
        Route::put('', [UserController::class, 'update']);
        Route::post('/logout', [UserController::class, 'logout']);
    });

    Route::prefix('profiles')->group(function () {
        Route::get('', [ProfileController::class, 'getAll']);
        Route::get('{id}', [ProfileController::class, 'get']);
        Route::post('', [ProfileController::class, 'create']);
        Route::put('', [ProfileController::class, 'update']);
        Route::delete('{id}', [ProfileController::class, 'delete']);
    });
});
