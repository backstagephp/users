<?php

namespace Backstage\Filament\Users\Resources\RoleResource;

use BackedEnum;
use Backstage\Filament\Users\Resources\RoleResource\Pages\CreateRole;
use Backstage\Filament\Users\Resources\RoleResource\Pages\EditRole;
use Backstage\Filament\Users\Resources\RoleResource\Pages\ListRoles;
use Backstage\Filament\Users\Resources\RoleResource\Pages\ViewRole;
use Backstage\Filament\Users\Resources\RoleResource\Schemas\RoleForm;
use Backstage\Filament\Users\Resources\RoleResource\Schemas\RoleInfolist;
use Backstage\Filament\Users\Resources\RoleResource\Tables\RolesTable;
use Filament\Panel;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Spatie\Permission\Models\Role;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?int $navigationSort = 1;

    public static function getSlug(?Panel $panel = null): string
    {
        return 'users-roles';
    }

    public static function getNavigationGroup(): ?string
    {
        return __('User management');
    }

    public static function getNavigationIcon(): string | BackedEnum | Htmlable | null
    {
        return Heroicon::OutlinedShieldCheck;
    }

    public static function getActiveNavigationIcon(): string | BackedEnum | Htmlable | null
    {
        return Heroicon::ShieldCheck;
    }

    public static function getPluralModelLabel(): string
    {
        return __('Roles');
    }

    public static function getModelLabel(): string
    {
        return __('Role');
    }

    public static function getNavigationLabel(): string
    {
        return static::getPluralModelLabel();
    }

    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RoleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'view' => ViewRole::route('/{record}'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }
}
