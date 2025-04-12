<?php

namespace Backstage\UserManagement\Resources\UserResource\Pages;

use Backstage\UserManagement\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Vormkracht10\Fields\Concerns\CanMapDynamicFields;

class EditUser extends EditRecord
{
    use CanMapDynamicFields;

    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function getFormFields()
    {
        return $this->resolveFormFields();
    }
}
