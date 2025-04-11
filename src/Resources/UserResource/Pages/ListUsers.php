<?php

namespace Backstage\UserManagement\Resources\UserResource\Pages;

use Backstage\UserManagement\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \Backstage\UserManagement\Widgets\StatsOverviewWidget::class,
        ];
    }
}
