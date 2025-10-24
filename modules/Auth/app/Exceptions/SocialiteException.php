<?php

namespace Modules\Auth\Exceptions;

use Exception;

class SocialiteException extends Exception
{
    public static function invalidSocialUser(): self
    {
        return new self(trans('auth.socialite.invalid_user'));
    }

    public static function cannotDisconnectOnlyMethod(): self
    {
        return new self(trans('auth.socialite.cannot_disconnect_only_method'));
    }

    public static function authenticationFailed(): self
    {
        return new self(trans('auth.socialite.error'));
    }

    public static function providerNotConnected($provider): self
    {
        return new self(trans('auth.socialite.not_connected', ['Provider' => $provider]));
    }
}
