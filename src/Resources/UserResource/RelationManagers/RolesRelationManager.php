<?php

namespace Backstage\Users\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class RolesRelationManager extends RelationManager
{
    protected static string $relationship = 'roles';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->roles->count();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute(function () {
                return '';
            })
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),

                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordTitleAttribute('name')
                    ->after(function ($record) {
                        $this->ownerRecord->assignRole($record);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\DetachAction::make()
                    ->after(function ($record) {
                        $this->ownerRecord->removeRole($record);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
