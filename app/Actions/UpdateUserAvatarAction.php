<?php

namespace App\Actions;

use App\Models\User;

class UpdateUserAvatarAction
{
    public function execute(User $user, ?string $avatarUrl): User
    {
        $user->update(['avatar_url' => $avatarUrl]);

        return $user->fresh();
    }

    public function updateToLatestProviderAvatar(User $user): User
    {
        // Update the user's avatar to the most recent provider's avatar if available
        $latestAccount = $user->socialAccounts()
            ->whereNotNull('provider_avatar_url')
            ->orderBy('last_login_at', 'desc')
            ->first();

        $avatarUrl = $latestAccount?->provider_avatar_url;

        return $this->execute($user, $avatarUrl);
    }
}
