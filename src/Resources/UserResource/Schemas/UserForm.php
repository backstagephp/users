<?php

namespace Backstage\Filament\Users\Resources\UserResource\Schemas;

use BackedEnum;
use Backstage\Filament\Users\Resources\RoleResource\RoleResource;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
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
                                            ->prefixIcon(fn(): BackedEnum => Heroicon::Envelope, true)
                                            ->email()
                                            ->required(),

                                        Select::make('roles')
                                            ->label(__('Roles'))
                                            ->visible(fn(Page $livewire): bool => $livewire instanceof CreateRecord)
                                            ->relationship(titleAttribute: fn(): string => 'name')
                                            ->prefixIcon(fn(): BackedEnum => RoleResource::getActiveNavigationIcon(), true)
                                            ->prefixIconColor(fn(): string => 'primary')
                                            ->preload()
                                            ->multiple()
                                            ->required()
                                            ->loadingMessage(fn(): string => __('Loading roles...'))
                                            ->maxItemsMessage(fn(Select $component): string => __('You can select up to :count roles.', ['count' => $component->getMaxItems()]))
                                            ->searchingMessage(fn(): string => __('Searching roles...'))
                                            ->placeholder(fn(): string => __('Select roles'))
                                            ->native(fn(): bool => false),

                                        TextInput::make('password')
                                            ->password()
                                            ->hidden(fn(TextInput $component): bool => $component->getLivewire() instanceof CreateRecord)
                                            ->prefixIcon(fn(): BackedEnum => Heroicon::LockClosed, true)
                                            ->revealable(),
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(6),

                        Fieldset::make()
                            ->hidden(fn(Fieldset $component): bool => $component->getLivewire() instanceof CreateRecord)
                            ->schema([
                                Section::make(__('Email verification'))
                                    ->description(__('Email verification is required for users.'))
                                    ->icon('heroicon-o-envelope-open')
                                    ->schema([
                                        DateTimePicker::make('email_verified_at')
                                            ->label(__('Email Verified'))
                                            ->live()
                                            ->prefixIcon(fn(DateTimePicker $component): BackedEnum => ! $component->getState() ? Heroicon::XCircle : Heroicon::CheckCircle, true)
                                            ->prefixIconColor(fn(DateTimePicker $component): string => ! $component->getState() ? 'danger' : 'success'),
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
