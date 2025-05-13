<?php

namespace Backstage\Filament\Users\Resources;

use Backstage\Filament\Users\Models\UsersTag;
use Backstage\Filament\Users\Resources\UsersTagResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UsersTagResource extends Resource
{
    protected static ?string $model = UsersTag::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationParentItem(): ?string
    {
        return config('backstage.users.resources.users', UserResource::class)::getNavigationLabel();
    }

    public static function getNavigationGroup(): ?string
    {
        return config('backstage.users.resources.users', UserResource::class)::getNavigationGroup();
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
