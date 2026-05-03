<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:web,client')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'createDefault'])->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'storeDefault']);
});

Route::middleware('guest:client')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('verify-otp', [OtpVerificationController::class, 'create'])
        ->name('otp.create');

    Route::post('verify-otp', [OtpVerificationController::class, 'verify'])
        ->name('otp.verify');

    Route::post('verify-otp/resend', [OtpVerificationController::class, 'resend'])
        ->name('otp.resend');

    Route::get('client/login', [AuthenticatedSessionController::class, 'createClient'])
        ->name('client.login');

    Route::post('client/login', [AuthenticatedSessionController::class, 'storeClient'])
        ->name('client.login.store');

    Route::get('client/register', [RegisteredUserController::class, 'create'])
        ->name('client.register');

    Route::post('client/register', [RegisteredUserController::class, 'store'])
        ->name('client.register.store');
});

Route::get('admin/login', [AuthenticatedSessionController::class, 'create'])
    ->name('admin.login');

Route::post('admin/login', [AuthenticatedSessionController::class, 'store'])
    ->name('admin.login.store');

Route::middleware('guest:admin,client')->group(function () {
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth:web,admin,owner,kasir,mekanik,client')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

Route::middleware('backend.auth')->group(function () {
    Route::post('admin/logout', [AuthenticatedSessionController::class, 'destroyAdmin'])
        ->name('admin.logout');
});

Route::middleware('auth:client')->group(function () {
    Route::post('client/logout', [AuthenticatedSessionController::class, 'destroyClient'])
        ->name('client.logout');
});
