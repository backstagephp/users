<?php

namespace Backstage\Users\Resources\UsersTagResource\Pages;

use Backstage\Users\Resources\UsersTagResource;
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
