<?php

namespace Modules\Auth\Exceptions;

use Exception;

/**
 * Exception used for authentication-related errors in the Auth module.
 *
 * This exception intentionally carries only a human-friendly message.
 * HTTP status mapping is the responsibility of the caller or the
 * global exception handler, which should convert the message into an
 * appropriate HTTP response when needed.
 */
class AuthException extends Exception
{
    /**
     * Create a new AuthException.
     *
     * This exception intentionally carries only a human-friendly message.
     * HTTP status handling is left to the caller (for example an exception
     * handler that maps exceptions to responses).
     *
     * @param  string  $message  Human-friendly error message.
     */
    public function __construct(string $message = 'Authentication error.')
    {
        parent::__construct($message);
    }

    /**
     * Factory for invalid credentials.
     *
     * Returns an exception with a localized message for failed authentication
     * and a 401 HTTP status code.
     */
    public static function invalidCredentials(): self
    {
        return new self(trans('auth.failed'));
    }

    /**
     * Factory for throttling / too many attempts.
     *
     * Builds an exception with a localized throttle message and a 429
     * (Too Many Requests) HTTP status code.
     *
     * @param  int  $seconds  Number of seconds until the client may retry.
     */
    public static function throttle(int $seconds = 60): self
    {
        $message = trans('auth.throttle', [
            'seconds' => $seconds,
            'minutes' => ceil($seconds / 60),
        ]);

        return new self($message);
    }
}
