<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\SendPasswordResetLinkAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): Response
    {
        return Inertia::render('auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request, SendPasswordResetLinkAction $sendLinkAction): RedirectResponse
    {
        $sendLinkAction->execute($request->input('email'));

        return back()->with('status', __('passwords.sent'));
    }
}
