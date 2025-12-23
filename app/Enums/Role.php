<?php

namespace App\Enums;

enum Role: string
{
    /**
     * Define role cases
     *
     * @case ADMIN - Administrator with full access to Filament panel and dashboard
     * @case USER - Regular user with limited access to dashboard
     */
    case ADMIN = 'admin';
    case USER = 'user';

    /**
     * Get the human-readable label for the role
     */
    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::USER => 'User',
        };
    }

    /**
     * Get all role values as array
     */
    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    /**
     * Get all role labels as array
     */
    public static function labels(): array
    {
        return array_map(fn ($case) => $case->label(), self::cases());
    }

    /**
     * Get role from string with default fallback
     */
    public static function fromString(?string $roleName): self
    {
        return self::tryFrom($roleName) ?? self::USER;
    }
}
