<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class Toast
{
    /* Type constants */
    const TYPE_DEFAULT = 'default';

    const TYPE_SUCCESS = 'success';

    const TYPE_ERROR = 'error';

    const TYPE_INFO = 'info';

    const TYPE_WARNING = 'warning';

    const TYPE_LOADING = 'loading';

    /* Position constants */
    const POSITION_TOP_LEFT = 'top-left';

    const POSITION_TOP_CENTER = 'top-center';

    const POSITION_TOP_RIGHT = 'top-right';

    const POSITION_BOTTOM_LEFT = 'bottom-left';

    const POSITION_BOTTOM_CENTER = 'bottom-center';

    const POSITION_BOTTOM_RIGHT = 'bottom-right';

    const POSITION_DEFAULT = self::POSITION_BOTTOM_RIGHT;

    private static function payload(
        string $message,
        string $type,
        ?string $description = null,
        ?array $action = null,
        ?int $duration = null,
        ?string $position = null
    ): array {
        return array_filter([
            'message' => $message,
            'type' => $type,
            'description' => $description,
            'action' => $action,
            'duration' => $duration,
            'position' => $position,
        ], fn ($value) => $value !== null);
    }

    /**
     * Show a toast notification.
     */
    public static function show(
        string $message,
        string $type = self::TYPE_SUCCESS,
        ?string $description = null,
        ?array $action = null,
        ?int $duration = null,
        ?string $position = null
    ): void {
        $toast = self::payload($message, $type, $description, $action, $duration, $position);

        // Inertia request (XHR)
        if (request()->header('X-Inertia')) {
            Inertia::flash('toast', $toast);

            return;
        }

        // Non-Inertia request (OAuth / full page redirect)
        Session::put('toast', $toast);
    }

    public static function default(
        string $message,
        ?string $description = null,
        ?array $action = null,
        ?int $duration = null,
        ?string $position = null
    ): void {
        self::show($message, self::TYPE_DEFAULT, $description, $action, $duration, $position);
    }

    public static function success(
        string $message,
        ?string $description = null,
        ?array $action = null,
        ?int $duration = null,
        ?string $position = null
    ): void {
        self::show($message, self::TYPE_SUCCESS, $description, $action, $duration, $position);
    }

    public static function error(
        string $message,
        ?string $description = null,
        ?array $action = null,
        ?int $duration = null,
        ?string $position = null
    ): void {
        self::show($message, self::TYPE_ERROR, $description, $action, $duration, $position);
    }

    public static function info(
        string $message,
        ?string $description = null,
        ?array $action = null,
        ?int $duration = null,
        ?string $position = null
    ): void {
        self::show($message, self::TYPE_INFO, $description, $action, $duration, $position);
    }

    public static function warning(
        string $message,
        ?string $description = null,
        ?array $action = null,
        ?int $duration = null,
        ?string $position = null
    ): void {
        self::show($message, self::TYPE_WARNING, $description, $action, $duration, $position);
    }

    public static function loading(
        string $message,
        ?string $description = null,
        ?array $action = null,
        ?int $duration = null,
        ?string $position = null
    ): void {
        self::show($message, self::TYPE_LOADING, $description, $action, $duration, $position);
    }
}
