<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

//TODO: add to auth module install doc
use Modules\Auth\Traits\HasSocialAccounts;

class User extends Authenticatable implements HasMedia
{
    use HasFactory,
        HasRoles,
        InteractsWithMedia,
        Notifiable,
        HasSocialAccounts;

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
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Set the user's email address.
     */
    protected function setEmailAttribute(string $value): void
    {
        $this->attributes['email'] = strtolower($value);
    }

    /**
     * Register media collections for this model
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatars')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    /**
     * Get avatar with fallback to default
     */
    public function getAvatarAttribute(): string
    {
        // First priority: Media library uploaded avatar
        $mediaAvatar = $this->getFirstMediaUrl('avatars');
        if ($mediaAvatar) {
            return $mediaAvatar;
        }

        // Second priority: Social login avatar URL from database
        if ($this->avatar_url) {
            return $this->avatar_url;
        }

        // Final fallback: Default avatar
        return asset('images/default-avatar.jpg');
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
