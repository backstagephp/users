<?php

namespace Backstage\Filament\Users\Widgets;

use Backstage\Filament\Users\Resources\UserResource\UserResource;
use Filament\Support\Colors\Color;
use Filament\Widgets\StatsOverviewWidget as WidgetsStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends WidgetsStatsOverviewWidget
{
    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        return [
            Stat::make(__('Total Users'), UserResource::getEloquentQuery()->count()),

            Stat::make(__('Verified Users'), UserResource::getEloquentQuery()->where('email_verified_at', '!=', null)->count())
                ->color(Color::Green)
                ->icon('heroicon-o-check-circle'),

            Stat::make(__('Pending Users'), UserResource::getEloquentQuery()->where('email_verified_at', null)->count()),
        ];
    }
}
