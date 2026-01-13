<?php

namespace Backstage\Filament\Users\Resources\UserResource\Pages;

use Filament\Actions;
use Filament\Support\Colors\Color;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Backstage\Filament\Users\Widgets\StatsOverviewWidget;
use Backstage\Filament\Users\Resources\UserResource\UserResource;

class ListUsers extends ListRecords
{
    public static function getResource(): string
    {
        return config('backstage.users.resources.users', UserResource::class);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'users' => Tab::make(__('Users'))
                ->modifyQueryUsing(fn () => static::getResource()::getEloquentQuery()->verified()),

            'pending' => Tab::make(__('Pending'))
                ->modifyQueryUsing(fn () => static::getResource()::getEloquentQuery()->unverified()),
        ];
    }
}
