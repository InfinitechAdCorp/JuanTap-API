<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\SocialController;
use App\Http\Controllers\API\TemplateController;
use App\Http\Controllers\API\SubscriptionController;
use App\Http\Controllers\API\TicketController;

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

Route::prefix('auth')->group(function () {
    Route::post('by-email', [AuthController::class, 'getByEmail']);
    Route::post('link-oauth', [AuthController::class, 'linkOAuth']);
    Route::post('signup', [AuthController::class, 'signupByCredentials']);
    Route::put('', [AuthController::class, 'upsert']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth.user')->group(function () {
        Route::get('', [AuthController::class, 'getAll']);
        Route::get('{id}', [AuthController::class, 'get']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::put('update/general', [AuthController::class, 'updateGeneralSettings']);
        Route::put('update/password', [AuthController::class, 'updatePassword']);
    });
});

Route::prefix('')->group(function () {
    Route::prefix('profiles')->group(function () {
        Route::get('', [ProfileController::class, 'getAll']);
        Route::get('{id}', [ProfileController::class, 'get']);
        Route::post('', [ProfileController::class, 'create']);
        Route::put('', [ProfileController::class, 'update']);
        Route::delete('{id}', [ProfileController::class, 'delete']);
    });

    Route::prefix('socials')->group(function () {
        Route::get('', [SocialController::class, 'getAll']);
        Route::get('{id}', [SocialController::class, 'get']);
        Route::post('', [SocialController::class, 'create']);
        Route::put('', [SocialController::class, 'update']);
        Route::delete('{id}', [SocialController::class, 'delete']);
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

        Route::post('set-status', [SubscriptionController::class, 'setStatus']);
    });

    Route::prefix('tickets')->group(function () {
        Route::get('', [TicketController::class, 'getAll']);
        Route::get('{id}', [TicketController::class, 'get']);
        Route::post('', [TicketController::class, 'create']);
        Route::put('', [TicketController::class, 'update']);
        Route::delete('{id}', [TicketController::class, 'delete']);

        Route::post('set-status', [TicketController::class, 'setStatus']);
    });
});

Route::prefix('user')->middleware('auth.user')->group(function () {});
