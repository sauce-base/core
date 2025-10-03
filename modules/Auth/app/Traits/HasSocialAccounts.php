<?php

namespace Modules\Auth\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Auth\Models\SocialAccount;

trait HasSocialAccounts
{
    /**
     * Get the social accounts associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function socialAccounts(): HasMany
    {
        return $this->hasMany(SocialAccount::class);
    }

    /**
     * Get connected providers for profile display
     */
    public function getConnectedProvidersAttribute(): array
    {
        return $this->socialAccounts()
            ->orderBy('last_login_at', 'desc')
            ->get(['provider', 'last_login_at', 'provider_avatar_url'])
            ->toArray();
    }
}
