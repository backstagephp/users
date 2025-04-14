<?php

namespace Backstage\UserManagement\Resources\UserResource\RelationManagers;

use Backstage\UserManagement\Resources\UsersTagResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TagsRelationManager extends RelationManager
{
    protected static string $relationship = 'usersTags';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->usersTags->count();
    }

    public function form(Form $form): Form
    {
        return UsersTagResource::form($form);
    }

    public function table(Table $table): Table
    {
        return UsersTagResource::table($table)
            ->headerActions([
                Tables\Actions\CreateAction::make(),

                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->multiple()
                    ->recordTitleAttribute('name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\DetachAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
