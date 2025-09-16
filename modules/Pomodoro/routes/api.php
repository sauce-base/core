<?php

use Illuminate\Support\Facades\Route;
use Modules\Pomodoro\Http\Controllers\PomodoroController;

Route::middleware(['auth:sanctum'])->prefix('api/v1/pomodoro')->group(function () {
    Route::apiResource('pomodoro', PomodoroController::class);
});
