<?php

namespace Modules\Localization\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocalizationController
{
    public function __invoke($locale)
    {
        App::setLocale($locale);
        Session::put('locale', $locale);

        return response(['locale' => App::getLocale()]);
    }
}
