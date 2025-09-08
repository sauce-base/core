<?php

namespace Modules\Auth\Http\Controllers;

use Modules\Auth\Actions\RegisterUserAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth::Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request, RegisterUserAction $registerAction): RedirectResponse
    {
        $registerAction->execute($request->name, $request->email, $request->password);

        return redirect(route('dashboard', absolute: false));
    }
}
