<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;

Route::middleware(['auth:sanctum'])->prefix('api/v1/auth')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
});
