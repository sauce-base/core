<?php

namespace App\Http\Controllers;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect(string $provider): RedirectResponse
    {
        $this->validateProvider($provider);

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();

            // Validate social user data
            if (! $this->validateSocialUser($socialUser)) {
                return redirect('/login')->withErrors([
                    'social' => 'Invalid social account data received.',
                ]);
            }

            $avatarUrl = $this->validateAvatarUrl($socialUser->getAvatar());

            // 1. Find existing social account for this provider
            $socialAccount = SocialAccount::where('provider', $provider)
                ->where('provider_id', $socialUser->getId())
                ->first();

            if ($socialAccount) {
                // Update existing social account and user avatar
                $socialAccount->update([
                    'provider_token' => $socialUser->token,
                    'provider_refresh_token' => $socialUser->refreshToken,
                    'provider_avatar_url' => $avatarUrl,
                    'last_login_at' => now(),
                ]);

                // Update user's current avatar to latest login
                $socialAccount->user->update(['avatar_url' => $avatarUrl]);

                Auth::login($socialAccount->user);

                return redirect()->intended('/dashboard');
            }

            // 2. Check if user exists with same email (account linking)
            $user = User::where('email', $socialUser->getEmail())->first();

            if (! $user) {
                // Create new user with avatar
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'email_verified_at' => now(),
                    'password' => bcrypt(str()->random(32)), // Random password
                    'avatar_url' => $avatarUrl,
                ]);
            } else {
                // Link to existing user and update avatar
                $user->update(['avatar_url' => $avatarUrl]);
            }

            // 3. Create social account with avatar and login tracking
            $user->socialAccounts()->create([
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'provider_token' => $socialUser->token,
                'provider_refresh_token' => $socialUser->refreshToken,
                'provider_avatar_url' => $avatarUrl,
                'last_login_at' => now(),
            ]);

            Auth::login($user);

            return redirect()->intended('/dashboard');

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
    public function disconnect(Request $request, string $provider): RedirectResponse
    {
        $user = $request->user();

        // Security: Don't disconnect if it's the only login method and no password
        if ($user->socialAccounts()->count() === 1 && ! $user->password) {
            return back()->withErrors([
                'social' => 'Cannot disconnect your only login method. Set a password first.',
            ]);
        }

        // Remove the social account
        $user->socialAccounts()->where('provider', $provider)->delete();

        // Update avatar to most recent remaining provider or null
        $latestAccount = $user->socialAccounts()
            ->whereNotNull('provider_avatar_url')
            ->orderBy('last_login_at', 'desc')
            ->first();

        $user->update([
            'avatar_url' => $latestAccount?->provider_avatar_url,
        ]);

        return back()->with('success', ucfirst($provider).' account disconnected successfully.');
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

    /**
     * Validate social user data
     */
    private function validateSocialUser($socialUser): bool
    {
        return $socialUser->getEmail() &&
               $socialUser->getId() &&
               filter_var($socialUser->getEmail(), FILTER_VALIDATE_EMAIL);
    }

    /**
     * Validate and sanitize avatar URL
     */
    private function validateAvatarUrl(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        return filter_var($url, FILTER_VALIDATE_URL) ? $url : null;
    }
}
