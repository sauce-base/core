<?php

use ___MODULE_NAMESPACE___\___Module___\Http\Controllers\___Module___Controller;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('api/v1/{module-}')->group(function () {
    Route::apiResource('{module-}', ___Module___Controller::class);
});
