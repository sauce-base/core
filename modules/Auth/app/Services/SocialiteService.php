<?php

namespace Modules\Auth\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Modules\Auth\Exceptions\SocialiteException;
use Modules\Auth\Models\SocialAccount;

class SocialiteService
{
    /**
     * Find or create a user from a Socialite user object.
     *
     * @throws SocialiteException
     */
    public function handleCallback(string $provider): User
    {
        /** @var SocialiteUser $socialiteUser */
        $socialiteUser = Socialite::driver($provider)->user();

        $this->validateSocialUser($socialiteUser);

        $avatarUrl = $this->validateAvatarUrl($socialiteUser->getAvatar());

        return DB::transaction(function () use ($provider, $socialiteUser, $avatarUrl) {
            // 1. Find existing social account for this provider with eager loading
            $socialAccount = SocialAccount::with('user')
                ->where('provider', $provider)
                ->where('provider_id', $socialiteUser->getId())
                ->first();

            if ($socialAccount) {
                return $this->updateExistingAccount($socialAccount, $socialiteUser, $avatarUrl);
            }

            // 2. Check if user exists with same email (account linking) with lock
            $user = User::lockForUpdate()
                ->where('email', $socialiteUser->getEmail())
                ->first();

            if (! $user) {
                $user = $this->createNewUser($socialiteUser, $avatarUrl);
            } else {
                $this->updateUserAvatar($user, $avatarUrl);
            }

            // 3. Create social account with avatar and login tracking
            $this->createSocialAccount($user, $provider, $socialiteUser, $avatarUrl);

            return $user;
        });
    }

    /**
     * Disconnect a provider from the given user.
     *
     * @throws \Modules\Auth\Exceptions\SocialiteException
     */
    public function disconnectProvider(User $user, string $provider): void
    {
        $socialAccounts = $user->socialAccounts;

        $providerAccount = $socialAccounts->where('provider', $provider)->first();

        if (! $providerAccount) {
            throw SocialiteException::providerNotConnected($provider);
        }

        if ($socialAccounts->count() === 1 && ! $user->password) {
            throw SocialiteException::cannotDisconnectOnlyMethod();
        }

        $providerAccount->delete();
    }

    private function updateExistingAccount(SocialAccount $socialAccount, SocialiteUser $socialiteUser, ?string $avatarUrl): User
    {

        // Update existing social account
        $socialAccount->update([
            'provider_token' => $socialiteUser->token,
            'provider_refresh_token' => $socialiteUser->refreshToken,
            'provider_avatar_url' => $avatarUrl,
            'last_login_at' => now(),
        ]);

        $user = $socialAccount->user;

        if (! $user instanceof User) {
            throw SocialiteException::invalidSocialUser();
        }

        $this->updateUserAvatar($user, $avatarUrl);

        return $user;
    }

    private function createNewUser(SocialiteUser $socialiteUser, ?string $avatarUrl): User
    {
        return User::create([
            'name' => $socialiteUser->getName() ?: $socialiteUser->getNickname(),
            'email' => $socialiteUser->getEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make(Str::random(32)), // Random password
            'avatar_url' => $avatarUrl,
        ]);
    }

    private function createSocialAccount(User $user, string $provider, SocialiteUser $socialiteUser, ?string $avatarUrl): SocialAccount
    {

        return SocialAccount::create([
            'user_id' => $user->id,
            'provider' => $provider,
            'provider_id' => $socialiteUser->getId(),
            'provider_token' => $socialiteUser->token,
            'provider_refresh_token' => $socialiteUser->refreshToken,
            'provider_avatar_url' => $avatarUrl,
            'last_login_at' => now(),
        ]);
    }

    private function updateUserAvatar(User $user, ?string $avatarUrl): void
    {
        if ($avatarUrl) {
            $user->update(['avatar_url' => $avatarUrl]);
        }
    }

    private function validateSocialUser(SocialiteUser $socialiteUser): void
    {
        if (
            ! $socialiteUser->getEmail() ||
            ! $socialiteUser->getId() ||
            ! filter_var($socialiteUser->getEmail(), FILTER_VALIDATE_EMAIL)
        ) {
            throw SocialiteException::invalidSocialUser();
        }
    }

    private function validateAvatarUrl(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        return filter_var($url, FILTER_VALIDATE_URL) ? $url : null;
    }
}
