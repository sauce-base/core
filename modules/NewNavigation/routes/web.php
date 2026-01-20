<?php

use Illuminate\Support\Facades\Route;
use Modules\NewNavigation\Http\Controllers\NewNavigationController;

Route::resource('new-navigation', NewNavigationController::class);
