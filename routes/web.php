<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\LocalizationController;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexController::class)
    ->name('index');

Route::post('/locale/{locale}', LocalizationController::class)
    ->name('locale');
