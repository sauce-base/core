<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminController extends Controller
{
    /**
     * Display the user management page
     */
    public function users()
    {
        $users = User::with('roles')
            ->orderBy('name')
            ->get()
            ->map(function ($user) {
                // Safely get user role with fallback
                $userRole = $user->getRoleNames()->first();
                $role = $userRole ? Role::fromString($userRole) : Role::USER;

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar_url' => $user->avatar,
                    'role' => [
                        'name' => $role->value,
                        'label' => $role->label(),
                    ],
                    'created_at' => $user->created_at->format('M j, Y'),
                ];
            });

        return Inertia::render('admin/UserManagement', [
            'users' => $users,
            'roles' => collect(Role::cases())->map(function ($role) {
                return [
                    'value' => $role->value,
                    'label' => $role->label(),
                ];
            })->toArray(),
            'currentUserId' => Auth::id(),
        ]);
    }

    /**
     * Update a user's role
     */
    public function updateUserRole(Request $request, User $user)
    {
        // Prevent users from changing their own role
        if ($user->id === Auth::id()) {
            return back()->withErrors([
                'role' => 'You cannot change your own role',
            ]);
        }

        $request->validate([
            'role' => 'required|string|in:'.implode(',', Role::values()),
        ]);

        $newRole = Role::from($request->role);

        // Remove all existing roles and assign the new one
        $user->syncRoles([$newRole->value]);

        return back()->with([
            'message' => "User role updated to {$newRole->label()}",
            'user' => [
                'id' => $user->id,
                'role' => [
                    'name' => $newRole->value,
                    'label' => $newRole->label(),
                ],
            ],
        ]);
    }
}
