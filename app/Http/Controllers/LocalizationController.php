<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocalizationController
{
    public function __invoke($locale)
    {
        $availableLocales = array_keys(config('app.available_locales', []));
        if (!in_array($locale, $availableLocales)) {
            return response(['error' => 'Invalid locale'], 400);
        }
        App::setLocale($locale);
        Session::put('locale', $locale);

        return response(['locale' => App::getLocale()]);
    }
}
