<?php

namespace Modules\Auth\Actions\Socialite;

use Modules\Auth\Actions\UpdateUserAvatarAction;
use Modules\Auth\Exceptions\SocialAuthException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\SocialAccount;
use App\Models\User;

class LinkSocialAccountAction
{
    public function __construct(
        private UpdateUserAvatarAction $updateAvatarAction
    ) {}

    public function execute(string $provider, object $socialUser): User
    {
        // Validate social user data
        $this->validateSocialUser($socialUser);

        $avatarUrl = $this->validateAvatarUrl($socialUser->getAvatar());

        // 1. Find existing social account for this provider
        $socialAccount = SocialAccount::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($socialAccount) {
            return $this->updateExistingAccount($socialAccount, $socialUser, $avatarUrl);
        }

        // 2. Check if user exists with same email (account linking)
        $user = User::where('email', $socialUser->getEmail())->first();

        if (! $user) {
            $user = $this->createNewUser($socialUser, $avatarUrl);
        } else {
            // Update existing user's avatar
            $this->updateAvatarAction->execute($user, $avatarUrl);
        }

        // 3. Create social account with avatar and login tracking
        $this->createSocialAccount($user, $provider, $socialUser, $avatarUrl);

        return $user;
    }

    private function updateExistingAccount(SocialAccount $socialAccount, object $socialUser, ?string $avatarUrl): User
    {
        // Update existing social account
        $socialAccount->update([
            'provider_token' => $socialUser->token,
            'provider_refresh_token' => $socialUser->refreshToken,
            'provider_avatar_url' => $avatarUrl,
            'last_login_at' => now(),
        ]);

        // Update user's current avatar to latest login
        $this->updateAvatarAction->execute($socialAccount->user, $avatarUrl);

        return $socialAccount->user;
    }

    private function createNewUser(object $socialUser, ?string $avatarUrl): User
    {
        return User::create([
            'name' => $socialUser->getName() ?: $socialUser->getNickname(),
            'email' => $socialUser->getEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make(Str::random(32)), // Random password
            'avatar_url' => $avatarUrl,
        ]);
    }

    private function createSocialAccount(User $user, string $provider, object $socialUser, ?string $avatarUrl): SocialAccount
    {
        return $user->socialAccounts()->create([
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'provider_token' => $socialUser->token,
            'provider_refresh_token' => $socialUser->refreshToken,
            'provider_avatar_url' => $avatarUrl,
            'last_login_at' => now(),
        ]);
    }

    private function validateSocialUser(object $socialUser): void
    {
        if (
            ! $socialUser->getEmail() ||
            ! $socialUser->getId() ||
            ! filter_var($socialUser->getEmail(), FILTER_VALIDATE_EMAIL)
        ) {
            throw SocialAuthException::invalidSocialUser();
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
