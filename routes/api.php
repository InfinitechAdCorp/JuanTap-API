<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TicketController;

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
    Route::post('signup', [UserController::class, 'signupByCredentials']);
    Route::put('', [UserController::class, 'upsert']);
    Route::post('login', [UserController::class, 'login']);
});

Route::middleware('auth.byId')->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('', [UserController::class, 'getAll']);
        Route::get('{id}', [UserController::class, 'get']);
        Route::post('logout', [UserController::class, 'logout']);
        Route::put('update/general', [UserController::class, 'updateGeneralSettings']);
        Route::put('update/password', [UserController::class, 'updatePassword']);
    });

    Route::prefix('profiles')->group(function () {
        Route::get('', [ProfileController::class, 'getAll']);
        Route::get('{id}', [ProfileController::class, 'get']);
        Route::put('', [ProfileController::class, 'upsert']);
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
        Route::post('publish/{id}', [TemplateController::class, 'publishTemplate']);
        Route::post('favorite/{id}', [TemplateController::class, 'favoriteTemplate']);
    });

    Route::prefix('subscriptions')->group(function () {
        Route::get('', [SubscriptionController::class, 'getAll']);
        Route::get('{id}', [SubscriptionController::class, 'get']);
        Route::post('', [SubscriptionController::class, 'create']);
        Route::put('', [SubscriptionController::class, 'update']);
        Route::delete('{id}', [SubscriptionController::class, 'delete']);
        Route::post('set-status', [SubscriptionController::class, 'delete']);
    });

    Route::prefix('tickets')->group(function () {
        Route::get('', [TicketController::class, 'getAll']);
        Route::get('{id}', [TicketController::class, 'get']);
        Route::post('', [TicketController::class, 'create']);
        Route::put('', [TicketController::class, 'update']);
        Route::delete('{id}', [TicketController::class, 'delete']);
        Route::post('set-status', [TicketController::class, 'delete']);
    });
});

Route::prefix('guest')->group(function () {
    Route::prefix('templates')->group(function () {
        Route::get('', [TemplateController::class, 'getAll']);
        Route::get('{id}', [TemplateController::class, 'get']);
    });
});
