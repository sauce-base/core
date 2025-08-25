<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
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
                Select::make('roles')
                    ->label('Role')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->minItems(1)
                    ->maxItems(1)
                    ->preload()
                    ->searchable()
                    // Optional: default to "user" on create:
                    ->default(fn() => [Role::where('name', 'user')->value('id')]),
                TextInput::make('email_verified_at')->readOnly(),
                TextInput::make('password')
                    ->password()
                    ->hiddenOn('edit'),
                TextInput::make('last_login_at')->readOnly(),
            ]);
    }
}
