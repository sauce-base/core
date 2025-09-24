<?php

namespace Modules\Auth\Exceptions;

use Exception;

class SocialiteException extends Exception
{
    public static function invalidSocialUser(): self
    {
        return new self('Invalid social account data received.');
    }

    public static function cannotDisconnectOnlyMethod(): self
    {
        return new self('Cannot disconnect your only login method. Set a password first.');
    }

    public static function authenticationFailed(): self
    {
        return new self('Authentication failed. Please try again.');
    }
}
