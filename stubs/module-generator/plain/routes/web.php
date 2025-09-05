<?php

use Illuminate\Support\Facades\Route;
use ___MODULE_NAMESPACE___\___Module___\Http\Controllers\___Module___Controller;

Route::middleware(['guest'])->group(function () {
    Route::resource('{module-}', ___Module___Controller::class)->names('{module-}');
});
