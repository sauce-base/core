<?php

namespace Modules\Auth\Filament\Clusters\Auth\Resources\Users\Pages;

use Modules\Auth\Filament\Clusters\Auth\Resources\Users\UserResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
