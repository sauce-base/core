<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as Trail;

// Dashboard (home)
Breadcrumbs::for('dashboard', function (Trail $trail) {
    $trail->push('dashboard', route('dashboard'));
});
