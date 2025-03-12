<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\SocialController;
use App\Http\Controllers\API\TemplateController;
use App\Http\Controllers\API\SubscriptionController;
use App\Http\Controllers\API\TicketController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\RecipientController;
use App\Http\Controllers\API\TestimonialController;

Route::prefix('auth')->group(function () {
    Route::post('link', [AuthController::class, 'link']);
    Route::post('signup', [AuthController::class, 'signupByCredentials']);
    Route::put('', [AuthController::class, 'upsert']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth.user')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
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
});

Route::prefix('user')->middleware('auth.user')->group(function () {
    Route::post('templates', [TicketController::class, 'create']);
});
