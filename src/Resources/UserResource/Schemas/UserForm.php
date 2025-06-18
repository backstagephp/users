<?php

namespace Backstage\Filament\Users\Resources\UserResource\Schemas;

use BackedEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(9)
                    ->schema([
                        Fieldset::make()
                            ->schema([
                                Section::make(__('User Information'))
                                    ->description(__('Basic information about the user.'))
                                    ->icon('heroicon-o-user-circle')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label(__('Name'))
                                            ->prefixIcon($schema->getLivewire()::getResource()::getActiveNavigationIcon(), true)
                                            ->required(),

                                        TextInput::make('email')
                                            ->label(__('Email'))
                                            ->prefixIcon(fn (): BackedEnum => Heroicon::Envelope, true)
                                            ->email()
                                            ->required(),

                                        TextInput::make('password')
                                            ->password()
                                            ->hidden(fn (TextInput $component): bool => $component->getLivewire() instanceof CreateRecord)
                                            ->prefixIcon(fn (): BackedEnum => Heroicon::LockClosed, true)
                                            ->revealable(),
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(6),

                        Fieldset::make()
                            ->hidden(fn (Fieldset $component): bool => $component->getLivewire() instanceof CreateRecord)
                            ->schema([
                                Section::make(__('Email verification'))
                                    ->description(__('Email verification is required for users.'))
                                    ->icon('heroicon-o-envelope-open')
                                    ->schema([
                                        DateTimePicker::make('email_verified_at')
                                            ->label(__('Email Verified'))
                                            ->live()
                                            ->prefixIcon(fn (DateTimePicker $component): BackedEnum => ! $component->getState() ? Heroicon::XCircle : Heroicon::CheckCircle, true)
                                            ->prefixIconColor(fn (DateTimePicker $component): string => ! $component->getState() ? 'danger' : 'success'),
                                    ])
                                    ->columns(1)
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(3),

                    ])
                    ->columnSpanFull(),
            ]);
    }
}
