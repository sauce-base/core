<?php

use Illuminate\Support\Facades\Route;
use Modules\Localization\Http\Controllers\LocalizationController;

Route::middleware(['auth:sanctum'])->prefix('api/v1/localization')->group(function () {
    Route::apiResource('localization', LocalizationController::class);
});
