<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Auth\Actions\LoginAction;
use Modules\Auth\Actions\LogoutAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class SessionController
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth::Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request, LoginAction $loginAction): RedirectResponse
    {
        $loginAction->execute(
            $request->email,
            $request->password,
            $request->boolean('remember'),
            $request->ip()
        );

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request, LogoutAction $logoutAction): RedirectResponse
    {
        $logoutAction->execute();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
