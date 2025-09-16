<?php

namespace Modules\Pomodoro\Http\Controllers;

use Inertia\Inertia;

class PomodoroController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Pomodoro::Index', [
            'message' => 'Welcome to Pomodoro Module',
            'module' => 'pomodoro',
        ]);
    }
}
