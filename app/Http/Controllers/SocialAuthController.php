<?php

namespace App\Http\Controllers;

use App\Actions\DisconnectSocialAccountAction;
use App\Actions\LinkSocialAccountAction;
use App\Exceptions\SocialAuthException;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect(string $provider): RedirectResponse
    {
        $this->validateProvider($provider);

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider, LinkSocialAccountAction $linkAction): RedirectResponse
    {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();

            $user = $linkAction->execute($provider, $socialUser);

            Auth::login($user);

            return redirect()->intended('/dashboard');

        } catch (SocialAuthException $e) {
            return redirect('/login')->withErrors([
                'social' => $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            return redirect('/login')->withErrors([
                'social' => 'Authentication failed. Please try again.',
            ]);
        }
    }

    public function providers(): JsonResponse
    {
        $enabledProviders = $this->getEnabledProviders();

        return response()->json([
            'providers' => $enabledProviders,
        ]);
    }

    /**
     * Disconnect a social provider from user account
     */
    public function disconnect(Request $request, string $provider, DisconnectSocialAccountAction $disconnectAction): RedirectResponse
    {
        $user = $request->user();

        try {
            $disconnectAction->execute($user, $provider);

            return back()->with('success', ucfirst($provider).' account disconnected successfully.');

        } catch (SocialAuthException $e) {
            return back()->withErrors([
                'social' => $e->getMessage(),
            ]);
        }
    }

    private function validateProvider(string $provider): void
    {
        $enabledProviders = array_keys($this->getEnabledProviders());

        if (! in_array($provider, $enabledProviders)) {
            abort(404);
        }
    }

    private function getEnabledProviders(): array
    {
        return collect(config('app.social_providers', []))
            ->filter(fn ($config) => $config['enabled'] ?? false)
            ->map(fn ($config) => ['name' => $config['name']])
            ->toArray();
    }
}
