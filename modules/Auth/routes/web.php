<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\SessionController;
use Modules\Auth\Http\Controllers\SocialiteController;

Route::prefix('auth')->group(function () {

    Route::middleware('guest')->group(function () {
        /**
         * Sign in
         */
        Route::get('login', [SessionController::class, 'create'])->name('login');
        Route::post('login', [SessionController::class, 'store']);

        /**
         * Sign up
         */
        Route::get('register', [SessionController::class, 'create'])->name('register');

        /**
         * Socialite 
         */
        Route::get('social/providers', [SocialiteController::class, 'providers'])->name('auth.social.providers');
        Route::get('social/{provider}', [SocialiteController::class, 'redirect'])->name('auth.social.redirect');
        Route::get('social/{provider}/callback', [SocialiteController::class, 'callback'])->name('auth.social.callback');
    });

    Route::middleware('auth')->group(function () {

        Route::post('logout', [SessionController::class, 'destroy']);

        /**
         * Socialite 
         */
        Route::delete('social/{provider}', [SocialiteController::class, 'disconnect'])->name('auth.social.disconnect');
    });
});
