<?php

namespace Backstage\Filament\Users\Resources\RoleResource\Pages;

use Backstage\Filament\Users\Resources\RoleResource\RoleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRole extends ViewRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
