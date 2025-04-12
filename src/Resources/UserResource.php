<?php

namespace Backstage\UserManagement\Resources;

use Backstage\UserManagement\Exports\UserExporter;
use Backstage\UserManagement\Imports\UserImporter;
use Backstage\UserManagement\Resources\UserResource\Pages;
use Backstage\UserManagement\Widgets\StatsOverviewWidget;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Vormkracht10\Fields\Filament\RelationManagers\FieldsRelationManager;

class UserResource extends Resource
{
    public static function getModel(): string
    {
        return config('backstage.user-management.eloquent.users.model', \App\Models\User::class);
    }

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(function ($livewire) {
                $livewire = $livewire;

                $formFields = $livewire->getFormFields();

                return array_merge([
                    Forms\Components\TextInput::make('name')
                        ->label(__('Name'))
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->label(__('Email'))
                        ->email()
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->label(__('Password'))
                        ->revealable(Filament::arePasswordsRevealable())
                        ->rule(Password::default())
                        ->autocomplete('new-password')
                        ->dehydrated(fn($state): bool => filled($state))
                        ->dehydrateStateUsing(fn($state): string => Hash::make($state))
                        ->live(debounce: 500),
                ], $formFields);
            });
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
        return [
            FieldsRelationManager::class
        ];
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
