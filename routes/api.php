<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\StatusController;
use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\PaymentTypeController;
use App\Http\Controllers\API\AppointmentController;

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [LoginController::class, 'logout']);

    Route::get('user', [UserController::class, 'current']);

    Route::patch('settings/profile', [ProfileController::class, 'update']);
    Route::patch('settings/password', [PasswordController::class, 'update']);
});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);

    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('password/reset', [ResetPasswordController::class, 'reset']);

    Route::post('email/verify/{user}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('email/resend', [VerificationController::class, 'resend']);

    Route::post('oauth/{driver}', [OAuthController::class, 'redirect']);
    Route::get('oauth/{driver}/callback', [OAuthController::class, 'handleCallback'])->name('oauth.callback');
});


Route::middleware('auth:api')->group( function () {

    Route::prefix('home')->group( function () {
        Route::get('/appointment', [HomeController::class, 'appointment']);
    });
    
    Route::apiResource('client', ClientController::class);
    Route::apiResource('category', CategoryController::class);
    Route::apiResource('status', StatusController::class);
    Route::apiResource('order', OrderController::class);

    Route::prefix('item')->group( function () {
        Route::get('/', [ItemController::class, 'index']);
        Route::get('/{id}', [ItemController::class, 'show']);
        Route::post('/', [ItemController::class, 'store']);
        Route::put('/{id}', [ItemController::class, 'update']);
        Route::delete('/{id}', [ItemController::class, 'destroy']);
    });

    Route::prefix('payment')->group( function () {
        Route::get('/', [PaymentController::class, 'index']);
    });

    Route::prefix('appointment')->group( function () {
        Route::get('/', [AppointmentController::class, 'index']);
        Route::get('/{id}', [AppointmentController::class, 'show']);
        Route::post('/', [AppointmentController::class, 'store']);
        Route::put('/{id}', [AppointmentController::class, 'update']);
        Route::delete('/{id}', [AppointmentController::class, 'destroy']);
    });
});
