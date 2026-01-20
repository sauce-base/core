<?php

use Modules\NewNavigation\Http\Controllers\NewNavigationController;
use Illuminate\Support\Facades\Route;

Route::resource('new-navigation', NewNavigationController::class);
