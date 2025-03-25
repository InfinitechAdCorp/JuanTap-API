<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\SocialController;
use App\Http\Controllers\API\TemplateController;
use App\Http\Controllers\API\SubscriptionController;
use App\Http\Controllers\API\TicketController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\RecipientController;
use App\Http\Controllers\API\TestimonialController;
use App\Http\Controllers\API\ChangeController;
use App\Http\Controllers\API\CustomizationController;
use App\Http\Controllers\API\UserController;

use App\Http\Controllers\UserSideController;

Route::prefix('auth')->group(function () {
    Route::post('get', [AuthController::class, 'get']);
    Route::post('get-by-email', [AuthController::class, 'getByEmail']);

    Route::post('link', [AuthController::class, 'link']);
    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('signin', [AuthController::class, 'signin']);
});

Route::prefix('')->middleware('auth.user')->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('get-counts', [DashboardController::class, 'getCounts']);
    });

    Route::prefix('users')->group(function () {
        Route::get('', [UserController::class, 'getAll']);
        Route::get('{id}', [UserController::class, 'get']);
    });

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
        Route::post('set-status', [SubscriptionController::class, 'setStatus']);

        Route::get('', [SubscriptionController::class, 'getAll']);
        Route::get('{id}', [SubscriptionController::class, 'get']);
        Route::post('', [SubscriptionController::class, 'create']);
        Route::put('', [SubscriptionController::class, 'update']);
        Route::delete('{id}', [SubscriptionController::class, 'delete']);
    });

    Route::prefix('tickets')->group(function () {
        Route::post('set-status', [TicketController::class, 'setStatus']);

        Route::get('', [TicketController::class, 'getAll']);
        Route::get('{id}', [TicketController::class, 'get']);
        Route::post('', [TicketController::class, 'create']);
        Route::put('', [TicketController::class, 'update']);
        Route::delete('{id}', [TicketController::class, 'delete']);
    });

    Route::prefix('payments')->group(function () {
        Route::post('set-status', [PaymentController::class, 'setStatus']);

        Route::get('', [PaymentController::class, 'getAll']);
        Route::get('{id}', [PaymentController::class, 'get']);
        Route::post('', [PaymentController::class, 'create']);
        Route::put('', [PaymentController::class, 'update']);
        Route::delete('{id}', [PaymentController::class, 'delete']);
    });

    Route::prefix('articles')->group(function () {
        Route::get('', [ArticleController::class, 'getAll']);
        Route::get('{id}', [ArticleController::class, 'get']);
        Route::post('', [ArticleController::class, 'create']);
        Route::put('', [ArticleController::class, 'update']);
        Route::delete('{id}', [ArticleController::class, 'delete']);
    });

    Route::prefix('testimonials')->group(function () {
        Route::get('', [TestimonialController::class, 'getAll']);
        Route::get('{id}', [TestimonialController::class, 'get']);
        Route::post('', [TestimonialController::class, 'create']);
        Route::put('', [TestimonialController::class, 'update']);
        Route::delete('{id}', [TestimonialController::class, 'delete']);
    });

    Route::prefix('recipients')->group(function () {
        Route::get('', [RecipientController::class, 'getAll']);
        Route::get('{id}', [RecipientController::class, 'get']);
        Route::post('', [RecipientController::class, 'create']);
        Route::put('', [RecipientController::class, 'update']);
        Route::delete('{id}', [RecipientController::class, 'delete']);
    });

    Route::prefix('customizations')->group(function () {
        Route::get('', [CustomizationController::class, 'getAll']);
        Route::get('{id}', [CustomizationController::class, 'get']);
        Route::post('', [CustomizationController::class, 'create']);
        Route::put('', [CustomizationController::class, 'update']);
        Route::delete('{id}', [CustomizationController::class, 'delete']);
    });

    Route::prefix('changes')->group(function () {
        Route::get('', [ChangeController::class, 'getAll']);
        Route::get('{id}', [ChangeController::class, 'get']);
        Route::post('', [ChangeController::class, 'create']);
        Route::put('', [ChangeController::class, 'update']);
        Route::delete('{id}', [ChangeController::class, 'delete']);
    });
});

Route::prefix('user')->middleware('auth.user')->group(function () {
    Route::get('changes/by-month', [UserSideController::class, 'getChangesByMonth']);
    Route::post('tickets/submit', [TicketController::class, 'create']);
    Route::get('tickets/track/{number}', [UserSideController::class, 'trackTicket']);
    Route::post('recipients/subscribe', [RecipientController::class, 'create']);
    Route::get('templates', [TemplateController::class, 'getAll']);
    Route::get('templates/view/{id}', [UserSideController::class, 'viewTemplate']);
    Route::post('templates/publish/{id}', [UserSideController::class, 'publishTemplate']);
    Route::post('templates/favorite/{id}', [UserSideController::class, 'favoriteTemplate']);
    Route::post('customizations/submit', [CustomizationController::class, 'create']);
    Route::post('settings/general', [UserSideController::class, 'generalSettings']);
    Route::post('settings/password', [UserSideController::class, 'passwordSettings']);
    Route::post('settings/profile', [UserSideController::class, 'profileSettings']);
});

Route::prefix('guest')->group(function () {
    Route::get('templates', [TemplateController::class, 'getAll']);
    Route::post('recipients/subscribe', [RecipientController::class, 'create']);
    Route::get('changes/by-month', [UserSideController::class, 'getChangesByMonth']);
});
