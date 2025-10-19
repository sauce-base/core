<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\EmailVerificationNotificationController;
use Modules\Auth\Http\Controllers\EmailVerificationPromptController;
use Modules\Auth\Http\Controllers\ForgotPasswordController;
use Modules\Auth\Http\Controllers\LoginController;
use Modules\Auth\Http\Controllers\PasswordController;
use Modules\Auth\Http\Controllers\RegisterController;
use Modules\Auth\Http\Controllers\ResetPasswordController;
use Modules\Auth\Http\Controllers\SocialiteController;
use Modules\Auth\Http\Controllers\VerifyEmailController;

Route::prefix('auth')->group(function () {
    Route::middleware('guest')->group(function () {

        Route::get('login', [LoginController::class, 'create'])
            ->name('login');

        Route::post('login', [LoginController::class, 'store']);

        Route::get('register', [RegisterController::class, 'create'])
            ->name('register');

        Route::post('register', [RegisterController::class, 'store']);

        Route::get('forgot-password', [ForgotPasswordController::class, 'create'])
            ->name('password.request');

        Route::post('forgot-password', [ForgotPasswordController::class, 'store'])
            ->name('password.email');

        Route::get('reset-password/{token}', [ResetPasswordController::class, 'create'])
            ->name('password.reset');

        Route::post('reset-password', [ResetPasswordController::class, 'store'])
            ->name('password.store');

        Route::get('socialite/{provider}', [SocialiteController::class, 'redirect'])
            ->name('auth.socialite.redirect');

        Route::get('socialite/{provider}/callback', [SocialiteController::class, 'callback'])
            ->name('auth.socialite.callback');
    });

    Route::middleware('auth')->group(function () {

        Route::post('logout', [LoginController::class, 'destroy'])
            ->name('logout');

        Route::get('verify-email', EmailVerificationPromptController::class)
            ->name('verification.notice');

        Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware('throttle:6,1')
            ->name('verification.send');

        Route::put('password', [PasswordController::class, 'update'])->name('password.update');

        Route::delete('socialite/{provider}', [SocialiteController::class, 'disconnect'])
            ->name('auth.social.disconnect');
    });
});
