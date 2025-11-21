<?php

namespace Backstage\Filament\Users\Resources\UserResource\Schemas;

use BackedEnum;
use Backstage\Filament\Users\Models\User;
use Filament\Facades\Filament;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;

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
                                Section::make()
                                    ->schema([
                                        ImageEntry::make('avatar')
                                            ->imageWidth('5rem')
                                            ->imageHeight('5rem')
                                            ->alignCenter()
                                            ->hiddenLabel()
                                            ->tooltip(__('User Avatar'))
                                            ->extraImgAttributes(function (User $record) {
                                                $ringColor = $record->hasVerifiedEmail() ? 'ring-green-500' : 'ring-red-500';

                                                return [
                                                    'class' => implode(' ', [
                                                        'ring ring-2',
                                                        'rounded-full',
                                                        $ringColor,
                                                    ]),
                                                ];
                                            })
                                            ->tooltip(fn (Model $record): ?string => $record->hasVerifiedEmail() ? __('User verfied!') : __('User unverified!'))
                                            ->state(fn (Model $record): ?string => Filament::getUserAvatarUrl($record)),

                                        TextEntry::make('name')
                                            ->hiddenLabel()
                                            ->alignCenter()
                                            ->copyable()
                                            ->iconColor('primary')
                                            ->icon(fn (): BackedEnum => Heroicon::User)
                                            ->tooltip(__('User Name')),

                                        TextEntry::make('email')
                                            ->hiddenLabel()
                                            ->alignCenter()
                                            ->copyable()
                                            ->iconColor('primary')
                                            ->icon(fn (): BackedEnum => Heroicon::Envelope)
                                            ->tooltip(__('User Email')),
                                    ])
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(3),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
