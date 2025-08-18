<?php

namespace App\Actions\Social;

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
        $validator = Validator::make([
            'provider' => $provider,
        ], [
            'provider' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $socialUser = Socialite::driver($provider)->user();

        return $this->linkAction->execute($provider, $socialUser);
    }
}
