<?php

namespace App\Observers;

use App\Enums\Role;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Assign the default User role to newly created users
        $user->assignRole(Role::USER->value);
    }
}
