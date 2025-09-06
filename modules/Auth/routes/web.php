<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;
use Modules\Auth\Http\Controllers\SessionController;

Route::prefix('auth')->group(function () {

    Route::middleware('guest')->group(function () {

        //TODO: remove this route
        Route::get('/', [AuthController::class, 'index']);


        Route::get('login', [SessionController::class, 'create'])->name('login');
        Route::post('login', [SessionController::class, 'store']);

        /**
         * TODO
         */
        Route::get('register', [SessionController::class, 'create'])->name('register');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [SessionController::class, 'destroy']);
    });
});
