<?php

namespace Modules\Auth\Filament\Clusters\Auth\Resources\Users;

use Modules\Auth\Filament\Clusters\Auth\Resources\Users\Pages\CreateUser;
use Modules\Auth\Filament\Clusters\Auth\Resources\Users\Pages\EditUser;
use Modules\Auth\Filament\Clusters\Auth\Resources\Users\Pages\ListUsers;
use Modules\Auth\Filament\Clusters\Auth\Resources\Users\Pages\ViewUser;
use Modules\Auth\Filament\Clusters\Auth\Resources\Users\Schemas\UserForm;
use Modules\Auth\Filament\Clusters\Auth\Resources\Users\Schemas\UserInfolist;
use Modules\Auth\Filament\Clusters\Auth\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Modules\Auth\Filament\Clusters\Auth\AuthCluster;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    protected static ?string $cluster = AuthCluster::class;

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UserInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
