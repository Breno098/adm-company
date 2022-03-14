<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\Auth\UserController as AuthUserController;

use App\Http\Controllers\API\Auth\ForgotPasswordController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\OAuthController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\ResetPasswordController;
use App\Http\Controllers\API\Auth\VerificationController;

use App\Http\Controllers\Settings\ProfileController;

use App\Http\Controllers\API\Tenant\UserController;
use App\Http\Controllers\API\Tenant\ClientController;
use App\Http\Controllers\API\Tenant\CategoryController;
use App\Http\Controllers\API\Tenant\OrderController;
use App\Http\Controllers\API\Tenant\StatusController;
use App\Http\Controllers\API\Tenant\AddressController;
use App\Http\Controllers\API\Tenant\PaymentController;
use App\Http\Controllers\API\Tenant\AppointmentController;
use App\Http\Controllers\API\Tenant\DocsController;
use App\Http\Controllers\API\Tenant\EmployeeController;
use App\Http\Controllers\API\Tenant\EmployeeReceiptController;
use App\Http\Controllers\API\Tenant\ExpenseController;
use App\Http\Controllers\API\Tenant\PositionController;
use App\Http\Controllers\API\Tenant\ProductController;
use App\Http\Controllers\API\Tenant\ReportFinanceController;
use App\Http\Controllers\API\Tenant\ServiceController;
use App\Http\Controllers\API\Tenant\RoleController;

/**
 * Auth routes
 */
Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);

    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('password/reset', [ResetPasswordController::class, 'reset']);

    // Route::post('email/verify/{user}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('email/resend', [VerificationController::class, 'resend']);

    Route::post('oauth/{driver}', [OAuthController::class, 'redirect']);
    Route::get('oauth/{driver}/callback', [OAuthController::class, 'handleCallback'])->name('oauth.callback');
});

/**
 * Tenant routes
 */
Route::middleware('auth:api')->group( function () {
    Route::post('logout', [LoginController::class, 'logout']);

    Route::get('address/searchCep', [AddressController::class, 'searchCep']);
    Route::get('client/count', [ClientController::class, 'count']);

    Route::middleware('deadline')->group( function () {
        Route::patch('profile/update', [ProfileController::class, 'update']);
        Route::patch('profile/password', [ProfileController::class, 'password']);
        Route::patch('profile/first-access', [ProfileController::class, 'firstAccess']);

        Route::get('user/current', [AuthUserController::class, 'current']);
        Route::get('user/can', [AuthUserController::class, 'can']);

        Route::get('employee-receipt/{employeeReceipt}/download', [EmployeeReceiptController::class, 'download']);

        Route::apiResource('client', ClientController::class);
        Route::apiResource('category', CategoryController::class);
        Route::apiResource('status', StatusController::class);
        Route::apiResource('order', OrderController::class);
        Route::apiResource('product', ProductController::class);
        Route::apiResource('service', ServiceController::class);
        Route::apiResource('appointment', AppointmentController::class);
        Route::apiResource('expense', ExpenseController::class);
        Route::apiResource('user', UserController::class);
        Route::apiResource('employee', EmployeeController::class);
        Route::apiResource('payment', PaymentController::class)->only(['index']);
        Route::apiResource('address', AddressController::class)->only(['index']);
        Route::apiResource('role', RoleController::class)->only(['index']);
        Route::apiResource('position', PositionController::class)->only(['index']);
        Route::apiResource('employee-receipt', EmployeeReceiptController::class);

        Route::get('docs/service-order/{order}/download', [DocsController::class, 'downloadServiceOrder']);
        Route::get('docs/budget/{order}/download', [DocsController::class, 'downloadBudget']);
        Route::get('docs/receipt/{order}/download', [DocsController::class, 'downloadReceipt']);
        Route::get('docs/warranty-order/{order}/download', [DocsController::class, 'downloadWarrantyOrder']);

        Route::get('report/finance/by-year', [ReportFinanceController::class, 'byYear']);
        Route::get('report/finance/by-month-and-year', [ReportFinanceController::class, 'byMonthAndYear']);

        Route::get('role/group-by-tag', [RoleController::class, 'indexGroupByTag']);

    });
});

/**
 * Administrator routes
 */
Route::middleware(['auth:api', 'administrator'])->prefix('admin')->name('admin.')->group( function () {

});


