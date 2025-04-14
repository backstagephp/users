<?php

namespace Backstage\UserManagement\Resources\UsersTagResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Backstage\UserManagement\Resources\UsersTagResource;

class ListUsersTags extends ListRecords
{
    protected static string $resource = UsersTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
