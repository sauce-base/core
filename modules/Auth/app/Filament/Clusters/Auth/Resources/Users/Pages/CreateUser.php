<?php

namespace Modules\Auth\Filament\Clusters\Auth\Resources\Users\Pages;

use Modules\Auth\Filament\Clusters\Auth\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
