<?php

namespace Backstage\UserManagement\Exports;

use Backstage\UserManagement\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class UserExporter extends Exporter
{
    public static function getModel(): string
    {
        return config('backstage.users.eloquent.users.model', User::class);
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('name')
                ->label(__('Name')),

            ExportColumn::make('email')
                ->label(__('Email')),

            ExportColumn::make('password')
                ->label(__('Password')),

            ExportColumn::make('email_verified_at')
                ->label(__('Email Verified At')),

            ExportColumn::make('created_at')
                ->label(__('Created At')),

            ExportColumn::make('updated_at')
                ->label(__('Updated At')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your user export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
