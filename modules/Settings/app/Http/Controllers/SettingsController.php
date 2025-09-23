<?php

namespace Modules\Settings\Http\Controllers;

use Inertia\Inertia;

class SettingsController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Settings::Index', [
            'message' => 'Welcome to Settings Module',
            'module' => 'settings',
        ]);
    }
}
