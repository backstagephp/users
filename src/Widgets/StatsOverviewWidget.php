<?php

namespace Backstage\UserManagement\Widgets;

use Backstage\UserManagement\Models\UserTraffic;
use Backstage\UserManagement\Resources\UserResource;
use Backstage\UserManagement\Resources\UserResource\Pages\ViewUser;
use Filament\Support\Colors\Color;
use Filament\Widgets\StatsOverviewWidget as WidgetsStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends WidgetsStatsOverviewWidget
{
    protected function getColumns(): int
    {
        return 3;
    }

    protected function getStats(): array
    {
        return [
            Stat::make(__('Total Users'), UserResource::getEloquentQuery()->count()),

            Stat::make(
                __('Daily user traffic'),
                UserTraffic::query()
                    ->selectRaw('DATE(created_at) as day, COUNT(*) as count')
                    ->groupBy('day')
                    ->get()
                    ->avg('count'),
            ),

            Stat::make(__('Verified Users'), UserResource::getEloquentQuery()->where('email_verified_at', '!=', null)->count())
                ->color(Color::Green)
                ->icon('heroicon-o-check-circle'),
        ];
    }
}
