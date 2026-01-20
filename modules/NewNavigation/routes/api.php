<?php

use Illuminate\Support\Facades\Route;
use Modules\NewNavigation\Http\Controllers\NewNavigationController;

Route::middleware(['auth:sanctum'])->prefix('api/v1/new-navigation')->group(function () {
    Route::apiResource('new-navigation', NewNavigationController::class, ['as' => 'api.new-navigation']);
});
