<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Modules\Auth\Actions\Socialite\DisconnectSocialAccountAction;
use Modules\Auth\Actions\Socialite\HandleProviderCallbackAction;
use Modules\Auth\Exceptions\SocialiteException;
use Symfony\Component\HttpFoundation\Response as RedirectResponse;

class SocialiteController extends Controller
{
    public function redirect(string $provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider, HandleProviderCallbackAction $providerCallbackAction): RedirectResponse
    {
        $user = $providerCallbackAction->execute($provider);

        Auth::login($user);

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Disconnect a social provider from user account
     */
    public function disconnect(string $provider, DisconnectSocialAccountAction $disconnectAction): RedirectResponse
    {
        $user = Auth::user();

        try {
            $disconnectAction->execute($user, $provider);

            return back()->with('status', 'social_account_disconnected');
        } catch (SocialiteException $e) {
            return back()->withErrors([
                'social' => $e->getMessage(),
            ]);
        }
    }
}
