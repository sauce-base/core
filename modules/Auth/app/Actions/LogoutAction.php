<?php

namespace Modules\Auth\Actions;

use Illuminate\Support\Facades\Auth;

class LogoutAction
{
    public function execute(): void
    {
        Auth::guard('web')->logout();
    }
}
