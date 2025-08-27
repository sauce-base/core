<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
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
                        ->description(__('Basic information about the user'))
                        ->inlineLabel()
                        ->schema(
                            [
                                ImageEntry::make('avatar')->circular(),
                                TextEntry::make('name')->label(__('Name')),
                                TextEntry::make('email')->label(__('Email address')),
                                TextEntry::make('created_at')->label(__('Created at'))->dateTime(),
                                TextEntry::make('updated_at')->label(__('Updated at'))->dateTime(),
                            ]
                        ),
                    Section::make()
                        ->inlineLabel()
                        ->description(__('User roles'))
                        ->schema(
                            [
                                TextEntry::make('roles.name')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'admin' => 'danger',
                                        'user' => 'primary',
                                        default => 'gray',
                                    })
                                    ->label(__('Role'))
                                    ->default(__('No role assigned')),
                            ]
                        ),
                    Section::make()
                        ->description(__('Last login and activity'))
                        ->schema(
                            [
                                TextEntry::make('last_login_at')
                                    ->label(__('Last login at'))
                                    ->dateTime(),
                                TextEntry::make('last_activity_at')
                                    ->label(__('Last activity at'))
                                    ->dateTime(),
                            ]
                        ),
                ]
            );
    }
}
