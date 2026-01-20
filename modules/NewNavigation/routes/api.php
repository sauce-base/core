<?php

use Modules\NewNavigation\Http\Controllers\NewNavigationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('api/v1/new-navigation')->group(function () {
    Route::apiResource('new-navigation', NewNavigationController::class, ['as' => 'api.new-navigation']);
});
