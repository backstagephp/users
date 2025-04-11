<?php

namespace Backstage\UserManagement\Resources\UserResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Backstage\UserManagement\Resources\UserResource;
use Backstage\UserManagement\Widgets\StatsOverviewWidget;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
