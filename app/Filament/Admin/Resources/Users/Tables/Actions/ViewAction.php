<?php

namespace App\Filament\Admin\Resources\Users\Tables\Actions;

use App\Filament\Admin\Resources\Users\Schemas\UserForm;
use Filament\Actions;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;

class ViewAction
{
    public static function make(): Actions\Action
    {
        return Actions\Action::make('view')
            ->schema(UserForm::configure())
            ->iconButton()
            ->label('View');
    }
}
