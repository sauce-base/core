<?php

namespace Modules\Auth\Actions\Socialite;

use Modules\Auth\Exceptions\SocialiteException;
use Modules\Auth\Actions\UpdateUserAvatarAction;
use App\Models\User;

class DisconnectSocialAccountAction
{
    public function __construct(
        private UpdateUserAvatarAction $updateAvatarAction
    ) {}

    public function execute(User $user, string $provider): User
    {
        // Security: Don't disconnect if it's the only login method and no password
        if ($user->socialAccounts()->count() === 1 && ! $user->password) {
            throw SocialiteException::cannotDisconnectOnlyMethod();
        }

        // Remove the social account
        $user->socialAccounts()->where('provider', $provider)->delete();

        // Update avatar to most recent remaining provider or null
        $this->updateAvatarAction->updateToLatestProviderAvatar($user);

        return $user->fresh();
    }
}
