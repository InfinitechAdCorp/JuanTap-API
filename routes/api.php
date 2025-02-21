<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\SubscriptionController;

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
    Route::post('by-email', [UserController::class, 'getByEmail']);
    Route::post('link-oauth', [UserController::class, 'linkOAuth']);
    Route::post('', [UserController::class, 'upsert']);
    Route::post('login', [UserController::class, 'login']);
});

Route::middleware('auth.admin')->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('', [UserController::class, 'getAll']);
        Route::get('{id}', [UserController::class, 'get']);
        Route::post('logout', [UserController::class, 'logout']);
    });

    Route::prefix('profiles')->group(function () {
        Route::get('', [ProfileController::class, 'getAll']);
        Route::get('{id}', [ProfileController::class, 'get']);
        Route::post('', [ProfileController::class, 'create']);
        Route::put('', [ProfileController::class, 'update']);
        Route::delete('{id}', [ProfileController::class, 'delete']);
    });

    Route::prefix('templates')->group(function () {
        Route::get('', [TemplateController::class, 'getAll']);
        Route::get('{id}', [TemplateController::class, 'get']);
        Route::post('', [TemplateController::class, 'create']);
        Route::put('', [TemplateController::class, 'update']);
        Route::delete('{id}', [TemplateController::class, 'delete']);
    });

    Route::prefix('subscriptions')->group(function () {
        Route::get('', [SubscriptionController::class, 'getAll']);
        Route::get('{id}', [SubscriptionController::class, 'get']);
        Route::post('', [SubscriptionController::class, 'create']);
        Route::put('', [SubscriptionController::class, 'update']);
        Route::delete('{id}', [SubscriptionController::class, 'delete']);
    });
});
