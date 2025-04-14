<?php

namespace Backstage\UserManagement\Resources\UsersTagResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Backstage\UserManagement\Resources\UsersTagResource;

class EditUsersTag extends EditRecord
{
    protected static string $resource = UsersTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
