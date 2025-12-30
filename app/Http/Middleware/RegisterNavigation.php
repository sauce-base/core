<?php

namespace App\Http\Middleware;

use App\Events\NavigationRegistering;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterNavigation
{
    /**
     * Handle an incoming request.
     *
     * Dispatch the NavigationRegistering event to allow all listeners
     * to register their navigation items. This ensures routes are fully
     * loaded before we try to use them.
     */
    public function handle(Request $request, Closure $next): Response
    {
        NavigationRegistering::dispatch();

        return $next($request);
    }
}
