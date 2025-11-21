<?php

namespace Backstage\Filament\Users\Imports;

use Backstage\Filament\Users\Models\User;
use Backstage\Filament\Users\Resources\UserResource\UserResource;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class UserImporter extends Importer
{
    public static function getModel(): string
    {
        return UserResource::getModel();
    }

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label(__('Name'))
                ->requiredMapping()
                ->rules(['required', 'max:255']),

            ImportColumn::make('email')
                ->label(__('Email'))
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),

            ImportColumn::make('email_verified_at')
                ->label(__('Email Verified At'))
                ->rules(['email', 'datetime']),

            ImportColumn::make('password')
                ->label(__('Password'))
                ->requiredMapping()
                ->rules(['required', 'max:255']),

            ImportColumn::make('created_at')
                ->label(__('Created At'))
                ->rules(['date']),
            ImportColumn::make('updated_at')
                ->label(__('Updated At'))
                ->rules(['date']),
        ];
    }

    public function resolveRecord(): ?User
    {
        // return User::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new User;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your user import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
