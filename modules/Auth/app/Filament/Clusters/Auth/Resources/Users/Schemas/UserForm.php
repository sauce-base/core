<?php

namespace Modules\Auth\Filament\Clusters\Auth\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

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
                    ->label(__('Email address'))
                    ->email()
                    ->required(),
                Select::make('roles')
                    ->label(__('Role'))
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->minItems(1)
                    ->maxItems(1)
                    ->preload()
                    ->searchable()
                    // Optional: default to "user" on create:
                    ->default(fn () => [Role::where('name', 'user')->value('id')]),
                TextInput::make('password')
                    ->label(__('Password'))
                    ->password()
                    ->hiddenOn('edit'),
                TextInput::make('password_confirmation')
                    ->label(__('Password confirmation'))
                    ->password()
                    ->hiddenOn('edit')
                    ->same('password'),
                TextInput::make('email_verified_at')
                    ->label(__('Email verified at'))
                    ->readOnly(),
                TextInput::make('last_login_at')
                    ->label(__('Last login at'))
                    ->readOnly(),
            ]);
    }
}
