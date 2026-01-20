<?php

namespace Modules\NewNavigation\Http\Controllers;

use Inertia\Inertia;

class NewNavigationController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('NewNavigation::Index', [
            'message' => 'Welcome to NewNavigation Module',
            'module' => 'newnavigation',
        ]);
    }
}
