<?php

use Illuminate\Support\Facades\Route;
use Modules\Pomodoro\Http\Controllers\PomodoroController;

Route::resource('pomodoro', PomodoroController::class);
