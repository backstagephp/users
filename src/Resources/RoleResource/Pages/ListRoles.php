<?php

namespace Backstage\Filament\Users\Resources\RoleResource\Pages;

use Backstage\Filament\Users\Resources\RoleResource\RoleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoles extends ListRecords
{
    public static function getResource(): string
    {
        return config('backstage.users.resources.roles', RoleResource::class);
    }
    
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
