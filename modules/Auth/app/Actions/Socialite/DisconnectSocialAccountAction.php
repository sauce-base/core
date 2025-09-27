<?php

namespace Modules\Auth\Actions\Socialite;

use App\Models\User;
use Modules\Auth\Actions\UpdateUserAvatarAction;
use Modules\Auth\Exceptions\SocialiteException;

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
