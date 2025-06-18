<?php

namespace Backstage\Filament\Users\Resources\RoleResource\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RoleForm
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
                                        TextInput::make('name')
                                            ->live()
                                            ->label(__('Name'))
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(ignoreRecord: true)
                                            ->columnSpanFull()
                                            ->placeholder(__('Enter role name')),
                                    ])
                                    ->columns(1)
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(6),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
