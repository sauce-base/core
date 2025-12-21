<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class DashboardController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Dashboard');
    }
}
