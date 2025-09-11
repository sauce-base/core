<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    public function __invoke($locale)
    {
        App::setLocale($locale);
        Session::put('locale', $locale);

        return response(['locale' => App::getLocale()]);
    }
}
