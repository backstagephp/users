<?php

namespace Backstage\Users\Resources;

use Backstage\Users\Exports\UserExporter;
use Backstage\Users\Imports\UserImporter;
use Backstage\Users\Models\User;
use Backstage\Users\Resources\UserResource\Pages;
use Backstage\Users\Scopes\VerifiedUser;
use Backstage\Users\Widgets\StatsOverviewWidget;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    public static function getModel(): string
    {
        return config('backstage.users.eloquent.users.model', User::class);
    }

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function getEloquentQuery(): Builder
    {
        return static::getModel()::query()
            ->withoutGlobalScopes([
                VerifiedUser::class,
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label(__('Email'))
                    ->email()
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\ImportAction::make('import')
                    ->importer(UserImporter::class)
                    ->color('primary'),

                Tables\Actions\ExportAction::make()
                    ->exporter(UserExporter::class),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tags')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        return $record->usersTags->pluck('name')->toArray();
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
        ];
    }
}
