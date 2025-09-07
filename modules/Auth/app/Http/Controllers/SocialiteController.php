<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Modules\Auth\Actions\Socialite\DisconnectSocialAccountAction;
use Modules\Auth\Actions\Socialite\GetEnabledProvidersAction;
use Modules\Auth\Actions\Socialite\HandleProviderCallbackAction;
use Modules\Auth\Actions\Socialite\ValidateProviderAction;
use Modules\Auth\Exceptions\SocialiteException;
use Exception;

class SocialiteController
{
    public function redirect(string $provider, ValidateProviderAction $validateAction): RedirectResponse
    {
        if (! $validateAction->execute($provider)) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider, ValidateProviderAction $validateAction, HandleProviderCallbackAction $callbackAction): RedirectResponse
    {
        if (! $validateAction->execute($provider)) {
            abort(404);
        }

        try {
            $user = $callbackAction->execute($provider);

            Auth::login($user);

            return redirect()->intended(route('dashboard'));
        } catch (SocialiteException $e) {
            return redirect(route('auth.login'))->withErrors([
                'social' => $e->getMessage(),
            ]);
        } catch (Exception $e) {
            // Log the exception or handle it as needed
            \Log::error('Social authentication failed', [
                'provider' => $provider,
                'error' => $e->getMessage(),
            ]);

            return redirect(route('auth.login'))->withErrors([
                'social' => __('Authentication failed. Please try again.'),
            ]);
        }
    }

    public function providers(GetEnabledProvidersAction $providersAction): JsonResponse
    {
        $enabledProviders = $providersAction->execute();

        return response()->json([
            'providers' => $enabledProviders,
        ]);
    }

    /**
     * Disconnect a social provider from user account
     */
    public function disconnect(string $provider, DisconnectSocialAccountAction $disconnectAction): RedirectResponse
    {
        $user = Auth::user();

        try {
            $disconnectAction->execute($user, $provider);

            return back()->with('success', __(':provider account disconnected successfully.', ['provider' => ucfirst($provider)]));
        } catch (SocialiteException $e) {
            return back()->withErrors([
                'social' => $e->getMessage(),
            ]);
        }
    }
}
