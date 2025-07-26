<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialAuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Index', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('index');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/social/{provider}', [SocialAuthController::class, 'disconnect'])->name('social.disconnect');
});

// Test routes for role-based access control
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/test', function () {
        return Inertia::render('Test/AdminTest', [
            'user' => auth()->user()->load('roles'),
            'message' => 'Welcome to the Admin Test Area, Chef Saucier! 🍯👨‍🍳',
        ]);
    })->name('admin.test');
});

Route::middleware(['auth', 'role:editor|admin'])->group(function () {
    Route::get('/editor/test', function () {
        return Inertia::render('Test/EditorTest', [
            'user' => auth()->user()->load('roles'),
            'message' => 'Welcome to the Editor Test Area! ✍️',
        ]);
    })->name('editor.test');
});

Route::middleware('auth')->group(function () {
    Route::get('/user/test', function () {
        $user = auth()->user();

        // Add some debug information to debugbar
        if (app()->bound('debugbar')) {
            debugbar()->info('User Role Debug Info', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'roles' => $user->getRoleNames()->toArray(),
                'role_enum' => $user->getRole(),
                'role_label' => $user->getRole()->label(),
                'role_description' => $user->getRole()->description(),
                'is_admin' => $user->isAdmin(),
                'is_editor' => $user->isEditor(),
                'is_author' => $user->isAuthor(),
                'is_user' => $user->isUser(),
            ]);
        }

        return Inertia::render('Test/UserTest', [
            'user' => $user->load('roles'),
            'message' => 'Welcome to the User Test Area! 👤',
            'role' => [
                'name' => $user->getRole()->value,
                'label' => $user->getRole()->label(),
                'description' => $user->getRole()->description(),
            ],
        ]);
    })->name('user.test');
});

// Social login routes
Route::group([], function () {
    Route::get('/auth/providers', [SocialAuthController::class, 'providers'])->name('social.providers');
    Route::get('/auth/{provider}', [SocialAuthController::class, 'redirect'])->name('social.redirect');
    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');
});

require __DIR__.'/auth.php';
