<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\UserController as AuthUserController;
use App\Http\Controllers\Auth\VerificationController;

use App\Http\Controllers\Settings\ProfileController;

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\StatusController;
use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\ApplicationPreferencesController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\AppointmentController;
use App\Http\Controllers\API\ExpenseController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\RoleController;

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [LoginController::class, 'logout']);

    Route::get('user/current', [AuthUserController::class, 'current']);

    Route::patch('profile/update', [ProfileController::class, 'update']);
    Route::patch('profile/password', [ProfileController::class, 'password']);
    Route::patch('profile/first-access', [ProfileController::class, 'firstAccess']);
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
    Route::prefix('address')->group( function () {
        Route::get('/searchCep', [AddressController::class, 'searchCep']);
    });
    
    Route::apiResource('client', ClientController::class);
    Route::apiResource('category', CategoryController::class);
    Route::apiResource('status', StatusController::class);
    Route::apiResource('order', OrderController::class);
    Route::apiResource('item', ItemController::class);
    Route::apiResource('product', ProductController::class);
    Route::apiResource('service', ServiceController::class);
    Route::apiResource('appointment', AppointmentController::class);
    Route::apiResource('app-preferences', ApplicationPreferencesController::class);
    Route::apiResource('expense', ExpenseController::class);
    Route::apiResource('user', UserController::class);
    Route::apiResource('payment', PaymentController::class)->only(['index']);
    Route::apiResource('address', AddressController::class)->only(['index']);
    Route::apiResource('role', RoleController::class)->only(['index']);
});
