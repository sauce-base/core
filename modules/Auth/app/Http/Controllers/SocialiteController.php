<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Modules\Auth\Actions\Socialite\DisconnectSocialAccountAction;
use Modules\Auth\Actions\Socialite\LinkSocialiteUser;
use Modules\Auth\Exceptions\SocialiteException;
use Symfony\Component\HttpFoundation\Response as RedirectResponse;

class SocialiteController extends Controller
{
    public function redirect(string $provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider, LinkSocialiteUser $linkSocialiteUser): RedirectResponse
    {
        $validator = Validator::make(['provider' => $provider], [
            'provider' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->with('error', trans('auth.socialite.error'));
        }

        $socialiteUser = Socialite::driver($provider)->user();

        $user = $linkSocialiteUser->execute($provider, $socialiteUser);

        Auth::login($user);

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Disconnect a social provider from user account
     */
    public function disconnect(string $provider, DisconnectSocialAccountAction $disconnectAction): RedirectResponse
    {
        // TODO: REFACTOR
        $user = Auth::user();

        try {
            $disconnectAction->execute($user, $provider);

            return back()->with('status', trans('auth.socialite.account_disconnected'));
        } catch (SocialiteException $e) {
            return back()->with(['error' => $e->getMessage()]);
        }
    }
}
