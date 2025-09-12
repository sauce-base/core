<?php

use Illuminate\Support\Facades\Route;
use Modules\Localization\Http\Controllers\LocalizationController;

Route::any('/locale/{locale}', LocalizationController::class)->name('locale');
