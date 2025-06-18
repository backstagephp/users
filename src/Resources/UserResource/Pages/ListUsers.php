<?php

namespace Backstage\Filament\Users\Resources\UserResource\Pages;

use Backstage\Filament\Users\Resources\UserResource\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
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
            \Backstage\Filament\Users\Widgets\StatsOverviewWidget::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'users' => Tab::make(__('Users'))
                ->badge(static::getResource()::getEloquentQuery()->verified()->count())
                ->badgeColor(Color::Green)
                ->modifyQueryUsing(fn ($query) => $query->verified()),

            'pending' => Tab::make(__('Pending'))
                ->badge(static::getResource()::getEloquentQuery()->unverified()->count())
                ->badgeColor(Color::Red)
                ->modifyQueryUsing(fn ($query) => $query->unverified()),
        ];
    }
}
