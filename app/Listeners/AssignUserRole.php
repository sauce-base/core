<?php

namespace App\Listeners;

use App\Enums\Role;
use Illuminate\Auth\Events\Registered;

class AssignUserRole
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        // Assign the default User role to newly registered users
        $event->user->assignRole(Role::USER->value);
    }
}
