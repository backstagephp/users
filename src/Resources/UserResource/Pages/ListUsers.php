<?php

namespace Backstage\UserManagement\Resources\UserResource\Pages;

use Backstage\UserManagement\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color;

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

    public function getTabs(): array
    {
        return [
            Tab::make(__('Users'))
                ->badge(static::getResource()::getEloquentQuery()->verified()->count())
                ->badgeColor(Color::Green)
                ->modifyQueryUsing(fn($query) => $query->verified()),

            Tab::make(__('Pending'))
                ->badge(static::getResource()::getEloquentQuery()->unverified()->count())
                ->badgeColor(Color::Red)
                ->modifyQueryUsing(fn($query) => $query->unverified()),
        ];
    }
}
