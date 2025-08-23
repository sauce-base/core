<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(
                [
                    Section::make()
                        ->description('Basic information about the user')
                        ->inlineLabel()
                        ->schema(
                            [
                                ImageEntry::make('avatar')->circular(),
                                TextEntry::make('name')->label('Name'),
                                TextEntry::make('email')->label('Email address'),
                                TextEntry::make('email_verified_at')->label('Email verified at')->dateTime(),
                                TextEntry::make('last_login_at')->label('Last login at')->dateTime(),
                                TextEntry::make('created_at')->label('Created at')->dateTime(),
                                TextEntry::make('updated_at')->label('Updated at')->dateTime(),
                            ]
                        ),
                    Section::make()
                        ->description('User roles and permissions')
                        ->schema(
                            [
                                TextEntry::make('role')->label('Role'),
                                TextEntry::make('permissions')->label('Permissions'),
                            ]
                        ),
                    Section::make()
                        ->description('Last login and activity')
                        ->schema(
                            [
                                TextEntry::make('last_login_at')->label('Last login at')->dateTime(),
                                TextEntry::make('last_activity_at')->label('Last activity at')->dateTime(),
                            ]
                        )
                ]
            );
    }
}
