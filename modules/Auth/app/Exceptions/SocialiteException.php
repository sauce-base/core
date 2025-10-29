<?php

namespace Modules\Auth\Exceptions;

use Exception;

class SocialiteException extends Exception
{
    public static function invalidSocialUser(): self
    {
        return new self(trans('socialite.invalid_user'));
    }

    public static function cannotDisconnectOnlyMethod(): self
    {
        return new self(trans('socialite.cannot_disconnect_only_method'));
    }

    public static function authenticationFailed(): self
    {
        return new self(trans('socialite.error'));
    }

    public static function providerNotConnected(string $provider): self
    {
        return new self(trans('socialite.not_connected', ['Provider' => $provider]));
    }
}
