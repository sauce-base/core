<?php

namespace Modules\Localization\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class HandleLocalization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($locale = Session::get('locale')) {

            $availableLocales = array_keys(config('localization.available_locales', []));

            if (isset($locale) && in_array($locale, $availableLocales)) {
                App::setLocale($locale);
            }
        }

        return $next($request);
    }
}
