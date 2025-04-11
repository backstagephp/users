<?php

namespace Backstage\UserManagement\Resources\UserResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Backstage\UserManagement\Resources\UserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
