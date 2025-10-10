<?php

namespace Modules\Auth\Actions\Socialite;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class HandleProviderCallbackAction
{
    public function __construct(
        private LinkSocialAccountAction $linkAction
    ) {}

    public function execute(string $provider): User
    {
        $validator = Validator::make(['provider' => $provider], [
            'provider' => 'required|string',
        ]);

        if (! $validator->fails()) {
            throw ValidationException::withMessages([
                'social' => trans('auth.socialite.error'),
            ]);
        }

        $user = Socialite::driver($provider)->user();

        return $this->linkAction->execute($provider, $user);
    }
}
