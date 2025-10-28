<?php

namespace Modules\Auth\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Modules\Auth\Exceptions\SocialiteException;
use Modules\Auth\Services\SocialiteService;
use Symfony\Component\HttpFoundation\Response as RedirectResponse;

class SocialiteController extends Controller
{
    private SocialiteService $socialiteService;

    public function __construct(SocialiteService $socialiteService)
    {
        $this->socialiteService = $socialiteService;
    }

    public function redirect(string $provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        $validator = Validator::make(['provider' => $provider], [
            'provider' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->with('error', trans('auth.socialite.error'));
        }

        $user = $this->socialiteService->handleCallback($provider);

        Auth::login($user);

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Disconnect a social provider from user account
     */
    public function disconnect(string $provider): RedirectResponse
    {
        $user = Auth::user();

        try {
            $this->socialiteService->disconnectProvider($user, $provider);

            return back()->with('status', trans('auth.socialite.account_disconnected'));
        } catch (SocialiteException $e) {
            return back()->with(['error' => $e->getMessage()]);
        }
    }
}
