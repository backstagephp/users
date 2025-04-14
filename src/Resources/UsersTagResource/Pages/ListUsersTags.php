<?php

namespace Backstage\UserManagement\Resources\UsersTagResource\Pages;

use Backstage\UserManagement\Resources\UsersTagResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

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
