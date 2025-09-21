<?php

namespace Modules\Auth\Filament\Clusters\Auth\Resources\Users\Pages;

use Modules\Auth\Filament\Clusters\Auth\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
