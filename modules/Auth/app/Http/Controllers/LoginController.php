<?php

namespace Modules\Auth\Http\Controllers;

use Modules\Auth\Exceptions\AuthException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Auth\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth::Login', [
            'status' => session('status'),
            'error' => session('error'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     * Login
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $user = $request->validateCredentials();
        } catch (AuthException $e) {
            return back()->with(['error' => $e->getMessage()]);
        }

        Auth::login($user, request()->boolean('remember'));

        request()->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
