<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\SocialController;
use App\Http\Controllers\API\TemplateController;
use App\Http\Controllers\API\SubscriptionController;
use App\Http\Controllers\API\TicketController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\ChangeController;
use App\Http\Controllers\API\RecipientController;
use App\Http\Controllers\API\CustomTemplateController;
use App\Http\Controllers\API\PaymentController;

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

Route::middleware('auth.user')->group(function () {
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
        Route::post('publish/{id}', [TemplateController::class, 'publish']);
        Route::post('favorite/{id}', [TemplateController::class, 'favorite']);
        Route::delete('favorite/{id}', [TemplateController::class, 'unfavorite']);
        Route::post('view/{id}', [TemplateController::class, 'view']);
    });

    Route::prefix('subscriptions')->group(function () {
        Route::get('', [SubscriptionController::class, 'getAll']);
        Route::get('{id}', [SubscriptionController::class, 'get']);
        Route::post('', [SubscriptionController::class, 'create']);
        Route::put('', [SubscriptionController::class, 'update']);
        Route::delete('{id}', [SubscriptionController::class, 'delete']);
        Route::post('set-status', [SubscriptionController::class, 'setStatus']);
    });

    Route::prefix('changes')->group(function () {
        Route::get('by-month', [ChangeController::class, 'getAllByMonth']);

        Route::get('', [ChangeController::class, 'getAll']);
        Route::get('{id}', [ChangeController::class, 'get']);
        Route::post('', [ChangeController::class, 'create']);
        Route::put('', [ChangeController::class, 'update']);
        Route::delete('{id}', [ChangeController::class, 'delete']);
    });

    Route::prefix('tickets')->group(function () {
        Route::get('', [TicketController::class, 'getAll']);
        Route::get('{id}', [TicketController::class, 'get']);
        Route::post('', [TicketController::class, 'create']);
        Route::put('', [TicketController::class, 'update']);
        Route::delete('{id}', [TicketController::class, 'delete']);
        Route::post('set-status', [TicketController::class, 'setStatus']);
        Route::get('track/{number}', [TicketController::class, 'track']);
    });

    Route::prefix('dashboard')->group(function () {
        Route::get('', [DashboardController::class, 'getAll']);
    });

    Route::prefix('recipients')->group(function () {
        Route::get('', [RecipientController::class, 'getAll']);
        Route::get('{id}', [RecipientController::class, 'get']);
        Route::post('', [RecipientController::class, 'create']);
        Route::put('', [RecipientController::class, 'update']);
        Route::delete('{id}', [RecipientController::class, 'delete']);
    });

    Route::prefix('custom-templates')->group(function () {
        Route::get('', [CustomTemplateController::class, 'getAll']);
        Route::get('{id}', [CustomTemplateController::class, 'get']);
        Route::post('', [CustomTemplateController::class, 'create']);
        Route::put('', [CustomTemplateController::class, 'update']);
        Route::delete('{id}', [CustomTemplateController::class, 'delete']);
    });

    Route::prefix('payments')->group(function () {
        Route::post('set-status', [PaymentController::class, 'setStatus']);

        Route::get('', [PaymentController::class, 'getAll']);
        Route::get('{id}', [PaymentController::class, 'get']);
        Route::post('', [PaymentController::class, 'create']);
        Route::put('', [PaymentController::class, 'update']);
        Route::delete('{id}', [PaymentController::class, 'delete']);
    });
});

Route::prefix('guest')->group(function () {
    Route::prefix('templates')->group(function () {
        Route::get('', [TemplateController::class, 'getAll']);
        Route::get('{id}', [TemplateController::class, 'get']);
        Route::post('view/{id}', [TemplateController::class, 'view']);
    });

    Route::prefix('changes')->group(function () {
        Route::get('by-month', [ChangeController::class, 'getAllByMonth']);
    });

    Route::prefix('recipients')->group(function () {
        Route::post('', [RecipientController::class, 'create']);
    });
});
