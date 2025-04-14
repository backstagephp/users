<?php

namespace Backstage\UserManagement\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Backstage\UserManagement\Models\UsersTag;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Backstage\UserManagement\Resources\UsersTagResource\Pages;
use App\Filament\Admin\Resources\UsersTagResource\RelationManagers;

class UsersTagResource extends Resource
{
    protected static ?string $model = UsersTag::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationParentItem(): ?string
    {
        return UserResource::getNavigationLabel();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsersTags::route('/'),
            'create' => Pages\CreateUsersTag::route('/create'),
            'edit' => Pages\EditUsersTag::route('/{record}/edit'),
        ];
    }
}
