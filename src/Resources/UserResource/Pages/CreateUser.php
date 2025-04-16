<?php

namespace Backstage\Users\Resources\UserResource\Pages;

use Backstage\Users\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
