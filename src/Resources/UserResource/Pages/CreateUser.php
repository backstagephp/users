<?php

namespace Backstage\UserManagement\Resources\UserResource\Pages;

use Backstage\UserManagement\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Vormkracht10\Fields\Concerns\CanMapDynamicFields;

class CreateUser extends CreateRecord
{
    use CanMapDynamicFields;

    protected static string $resource = UserResource::class;

    public function getFormFields()
    {
        return $this->resolveFormFields();
    }
}
