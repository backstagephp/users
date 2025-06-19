<?php

namespace Backstage\Filament\Users\Resources\UserResource\Schemas;

use BackedEnum;
use Backstage\Filament\Users\Models\User;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class UserInfolist
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
                                        TextEntry::make('name')
                                            ->label(__('Name'))
                                            ->icon(fn(): BackedEnum => $schema->getLivewire()::getResource()::getActiveNavigationIcon(), true),

                                        TextEntry::make('email')
                                            ->label(__('Email'))
                                            ->icon(fn(): BackedEnum => Heroicon::Envelope, true),
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(6),

                        Fieldset::make()
                            ->visible(fn(User $record): bool => $record->hasVerifiedEmail())
                            ->schema([
                                Section::make(__('Email verification'))
                                    ->description(__('Email verification is required for users.'))
                                    ->icon('heroicon-o-envelope-open')
                                    ->schema([
                                        TextEntry::make('email_verified_at')
                                            ->label(__('Email Verified At'))
                                            ->sinceTooltip()
                                            ->icon(fn(TextEntry $component): BackedEnum => $component->getDefaultState() === $component->getState() ? Heroicon::XCircle : Heroicon::CheckCircle, true)
                                            ->iconColor(fn(TextEntry $component): string => $component->getDefaultState() === $component->getState() ? 'danger' : 'success')
                                            ->default(__('Not Verified')),
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
