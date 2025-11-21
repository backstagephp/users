<?php

namespace Backstage\Filament\Users\Resources\RoleResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class RoleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(9)
                    ->schema([
                        Fieldset::make()
                            ->schema([
                                Section::make(__('Role Information'))
                                    ->description(__('Define the role details below.'))
                                    ->icon('heroicon-o-shield-check')
                                    ->schema([
                                        TextEntry::make('name')
                                            ->label(__('Name'))
                                            ->placeholder(__('Enter role name')),

                                        TextEntry::make('users.count')
                                            ->label(__('Users'))
                                            ->formatStateUsing(fn (Role $record) => $record->users->count())
                                            ->default(0)
                                            ->icon('heroicon-o-users')
                                            ->badge(),
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(6),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
