<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LocalizationController;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexController::class)
    ->name('index');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::post('/locale/{locale}', LocalizationController::class)
    ->name('locale');
