<?php

namespace App\Http\Controllers;

use App\Actions\Social\DisconnectSocialAccountAction;
use App\Actions\Social\GetEnabledProvidersAction;
use App\Actions\Social\HandleProviderCallbackAction;
use App\Actions\Social\ValidateProviderAction;
use App\Exceptions\SocialAuthException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
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

            return redirect()->intended('/dashboard');
        } catch (SocialAuthException $e) {
            return redirect('/login')->withErrors([
                'social' => $e->getMessage(),
            ]);
        } catch (Exception $e) {
            // Log the exception or handle it as needed
            \Log::error('Social authentication failed', [
                'provider' => $provider,
                'error' => $e->getMessage(),
            ]);

            return redirect('/login')->withErrors([
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
        } catch (SocialAuthException $e) {
            return back()->withErrors([
                'social' => $e->getMessage(),
            ]);
        }
    }
}
