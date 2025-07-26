<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case EDITOR = 'editor';
    case AUTHOR = 'author';
    case USER = 'user';

    /**
     * Get the human-readable label for the role
     */
    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::EDITOR => 'Editor',
            self::AUTHOR => 'Author',
            self::USER => 'User',
        };
    }

    /**
     * Get a detailed description of the role's capabilities
     */
    public function description(): string
    {
        return match ($this) {
            self::ADMIN => 'Full system access with user management capabilities',
            self::EDITOR => 'Content management and publication permissions',
            self::AUTHOR => 'Content creation and editing permissions',
            self::USER => 'Basic user with limited permissions',
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
