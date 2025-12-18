<?php

namespace Backstage\Filament\Users\Resources\RoleResource\Pages;

use Backstage\Filament\Users\Resources\RoleResource\RoleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRole extends ViewRecord
{
    public static function getResource(): string
    {
        return config('backstage.users.resources.roles', RoleResource::class);
    }
    
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
