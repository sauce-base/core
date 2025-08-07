<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('auths', AuthController::class)->names('auth');
});
