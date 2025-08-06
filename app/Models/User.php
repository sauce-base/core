<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Role;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function socialAccounts(): HasMany
    {
        return $this->hasMany(SocialAccount::class);
    }

    /**
     * Get avatar with fallback to default
     */
    public function getAvatarAttribute(): string
    {
        return $this->avatar_url ?: '/images/default-avatar.jpg';
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

    /**
     * Check if user is an administrator
     *
     * @return bool True if the user has admin role
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(Role::ADMIN);
    }

    /**
     * Check if user is an editor
     *
     * @return bool True if the user has editor role
     */
    public function isEditor(): bool
    {
        return $this->hasRole(Role::EDITOR);
    }

    /**
     * Check if user is an author
     *
     * @return bool True if the user has author role
     */
    public function isAuthor(): bool
    {
        return $this->hasRole(Role::AUTHOR);
    }

    /**
     * Check if user is a regular user
     *
     * @return bool True if the user has user role
     */
    public function isUser(): bool
    {
        return $this->hasRole(Role::USER);
    }

    /**
     * Get the user's role as an enum
     *
     * @return Role The user's role
     */
    public function getRole(): Role
    {
        return Role::fromString($this->getRoleNames()->first());
    }

    /**
     * Get user role label for display purposes
     *
     * @return string The human-readable role label
     */
    public function getRoleLabelAttribute(): string
    {
        $role = Role::fromString($this->getRoleNames()->first());

        return $role->label();
    }

    /**
     * Get user role description
     *
     * @return string The role description
     */
    public function getRoleDescriptionAttribute(): string
    {
        $role = Role::fromString($this->getRoleNames()->first());

        return $role->description();
    }
}
