<?php

namespace Backstage\UserManagement\Resources\UsersTagResource\Pages;

use Backstage\UserManagement\Resources\UsersTagResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

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
