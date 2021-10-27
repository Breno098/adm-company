<?php

use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\Auth\ForgotPasswordController;
// use App\Http\Controllers\Auth\OAuthController;
// use App\Http\Controllers\Auth\RegisterController;
// use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\UserController as AuthUserController;
// use App\Http\Controllers\Auth\VerificationController;

use App\Http\Controllers\Settings\ProfileController;

use App\Http\Controllers\API\Application\UserController;
use App\Http\Controllers\API\Application\ClientController;
use App\Http\Controllers\API\Application\CategoryController;
use App\Http\Controllers\API\Application\OrderController;
use App\Http\Controllers\API\Application\StatusController;
use App\Http\Controllers\API\Application\AddressController;
use App\Http\Controllers\API\Application\PaymentController;
use App\Http\Controllers\API\Application\AppointmentController;
use App\Http\Controllers\API\Application\ExpenseController;
use App\Http\Controllers\API\Application\ProductController;
use App\Http\Controllers\API\Application\ServiceController;
use App\Http\Controllers\API\Application\RoleController;
use App\Http\Controllers\API\Application\Auth\LoginController;

use App\Http\Controllers\API\Administrator\Auth\LoginController as AdministratorLoginController;

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [LoginController::class, 'logout']);

    Route::get('user/current', [AuthUserController::class, 'current']);

    Route::patch('profile/update', [ProfileController::class, 'update']);
    Route::patch('profile/password', [ProfileController::class, 'password']);
    Route::patch('profile/first-access', [ProfileController::class, 'firstAccess']);
});

// Route::group(['middleware' => 'guest:api'], function () {
//     Route::post('login', [LoginController::class, 'login']);
//     Route::post('register', [RegisterController::class, 'register']);

//     Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
//     Route::post('password/reset', [ResetPasswordController::class, 'reset']);

//     Route::post('email/verify/{user}', [VerificationController::class, 'verify'])->name('verification.verify');
//     Route::post('email/resend', [VerificationController::class, 'resend']);
// });

Route::middleware('guest:api')->group( function () {
    Route::post('login', [LoginController::class, 'login'])->name('login');

    Route::post('admin/login', [AdministratorLoginController::class, 'login'])->name('admin.login');

    // Route::post('oauth/{driver}', [OAuthController::class, 'redirect']);
    // Route::get('oauth/{driver}/callback', [OAuthController::class, 'handleCallback'])->name('oauth.callback');
});


Route::middleware('auth:api')->group( function () {
    Route::middleware('deadline')->group( function () {
        Route::get('address/searchCep', [AddressController::class, 'searchCep']);
        Route::get('client/count', [ClientController::class, 'count']);
        
        Route::apiResource('client', ClientController::class);
        Route::apiResource('category', CategoryController::class);
        Route::apiResource('status', StatusController::class);
        Route::apiResource('order', OrderController::class);
        Route::apiResource('product', ProductController::class);
        Route::apiResource('service', ServiceController::class);
        Route::apiResource('appointment', AppointmentController::class);
        Route::apiResource('expense', ExpenseController::class);
        Route::apiResource('user', UserController::class);
        Route::apiResource('payment', PaymentController::class)->only(['index']);
        Route::apiResource('address', AddressController::class)->only(['index']);
        Route::apiResource('role', RoleController::class)->only(['index']);
    });

    Route::middleware('administrator')->prefix('admin')->name('admin.')->group( function () {

    });
});
