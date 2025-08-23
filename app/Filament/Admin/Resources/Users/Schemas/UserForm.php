<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                SpatieMediaLibraryFileUpload::make('avatar_url')
                    ->avatar()
                    ->directory('avatars')
                    ->collection('avatars')
                    ->disk('public'),
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('email_verified_at')->readOnly(),
                TextInput::make('password')
                    ->password()
                    ->hiddenOn('edit'),
                TextInput::make('last_login_at')->readOnly(),
            ]);
    }
}
