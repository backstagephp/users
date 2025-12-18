<?php

namespace Backstage\Filament\Users\Resources\RoleResource\Pages;

use Backstage\Filament\Users\Resources\RoleResource\RoleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    public static function getResource(): string
    {
        return config('backstage.users.resources.roles', RoleResource::class);
    }
}
