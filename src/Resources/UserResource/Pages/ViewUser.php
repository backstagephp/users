<?php

namespace Backstage\Filament\Users\Resources\UserResource\Pages;

use BackedEnum;
use Backstage\Filament\Users\Actions\GenerateSignedRegistrationUri;
use Backstage\Filament\Users\Models\User;
use Backstage\Filament\Users\Resources\UserResource\UserResource;
use Backstage\Laravel\Users\Eloquent\Models\UserTraffic;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Auth\Notifications\ResetPassword;
use Filament\Auth\Notifications\VerifyEmail;
use Filament\Facades\Filament;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class ViewUser extends ViewRecord implements HasTable
{
    use InteractsWithTable {
        makeTable as makeBaseTable;
    }

    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('open_register_link')
                ->label(__('Open Registration Link'))
                ->icon('heroicon-o-link')
                ->action(function (Model $record) {
                    $url = GenerateSignedRegistrationUri::run(user: $record);

                    return redirect()->away($url);
                })
                ->visible(fn(User $record): bool => $record->userIsRegistered() === false)
                ->requiresConfirmation()
                ->modalDescription(__('This action will open the registration link for this user. By confirming, you will be redirected to the registration page and logged out of your current session.')),

            Actions\ActionGroup::make([
                Actions\Action::make('send_verify_user_email')
                    ->visible(fn(User $record): bool => $record->hasVerifiedEmail() === false)
                    ->label(__('Send Verification Email'))
                    ->action(function ($record) {
                        $notification = new VerifyEmail;
                        $notification->url = Filament::getVerifyEmailUrl($record);
                        $record->notify($notification);
                    })
                    ->requiresConfirmation(),

                Actions\Action::make('send_password_reset_email')
                    ->label(__('Send Password Reset Email'))
                    ->action(function (): void {
                        /**
                         * @var User $user
                         * @var \Illuminate\Contracts\Auth\Authenticatable $user
                         */
                        $user = $this->record;
                        /**
                         * Broker
                         *
                         * @var \Illuminate\Contracts\Auth\PasswordBroker $broker
                         */
                        $broker = app('auth.password.broker');

                        $token = $broker->createToken($user);
                        $notification = new ResetPassword($token);
                        $notification->url = Filament::getResetPasswordUrl($token, $user);
                        $user->notify($notification);
                    })
                    ->requiresConfirmation(),
            ])
                ->button()
                ->label(__('Security Actions'))
                ->icon(false)
                ->dropdownPlacement('bottom'),

            Actions\EditAction::make(),
        ];
    }

    public function getSubheading(): string | Htmlable | null
    {
        /**
         * @var User $user
         */
        $user = $this->record;

        $verified = $user->hasVerifiedEmail();

        $string = str(
            '<div class="text-sm text-gray-500 dark:text-gray-400">
                ' . __('Verified :time', [
                'time' => $user->email_verified_at?->diffForHumans(),
            ]) . '
            </div>'
        );

        if (! $verified) {
            $string = str(
                '<div class="text-sm text-red-500">
                    ' . __('Unverified') . '
                </div>'
            );
        }

        return new HtmlString(Blade::render($string->toString()));
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn(): Builder => $this->record->traffic()->whereNot('path', 'livewire/update')->orderByDesc('created_at')->getQuery())
            ->heading(fn($table): Htmlable => new HtmlString(Blade::render('<filament::icon icon="heroicon-m-user"/>' . __('User Traffic (:count)', [
                'count' => $table->getQuery()->count(),
            ]))))
            ->searchable()
            ->paginated([4])
            ->columns([
                TextColumn::make('path')
                    ->label(__('Path'))
                    ->searchable(),
            ])
            ->headerActions([
                Action::make('reset')
                    ->label(__('Reset position'))
                    ->icon(fn(): BackedEnum => Heroicon::ArrowUturnLeft)
                    ->url(fn(): string => $this->getUrl([
                        'record' => $this->record,
                    ]))
                    ->visible(function () {
                        $tableRecords = $this->getTableRecords();

                        $firstFourRecords = $this->getTable()->getQuery()->take(4)->get();

                        $recordIds = $firstFourRecords->pluck('id')->toArray();
                        $tableRecords = $tableRecords->pluck('id')->toArray();

                        if ($tableRecords === $recordIds) {
                            return false;
                        }

                        return true;
                    }),
            ])
            ->recordAction('view')
            ->recordActions([
                Action::make('visit')
                    ->button()
                    ->hiddenLabel()
                    ->color(fn(): string => 'primary')
                    ->tooltip(fn(): string => __('Visit Path'))
                    ->icon(fn(): BackedEnum => Heroicon::ArrowTopRightOnSquare)
                    ->url(fn(UserTraffic $record): string => $record->getAttribute('full_url'), true),

                Action::make('view')
                    ->button()
                    ->hiddenLabel()
                    ->color(fn(): string => 'gray')
                    ->tooltip(fn(): string => __('View Traffic'))
                    ->icon(fn(): BackedEnum => Heroicon::Eye)
                    ->slideOver()
                    ->modal()
                    ->modalIcon(fn(): BackedEnum => Heroicon::Eye)
                    ->modalHeading(fn(): string => __('Traffic Details'))
                    ->modalDescription(fn(UserTraffic $record): Htmlable => new HtmlString(__('Traffic details for :path', [
                        'path' => '<a href="' . e($record->getAttribute('full_url')) . '" target="_blank" class="text-primary-600 underline">' . e($record->getAttribute('path')) . '</a>',
                    ])))
                    ->schema([
                        Section::make(__('Request Information'))
                            ->description(__('Details about the HTTP request.'))
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextEntry::make('method')
                                            ->label(__('Method'))
                                            ->badge()
                                            ->color(fn(string $state): string => match ($state) {
                                                'GET' => 'success',
                                                'POST' => 'primary',
                                                'PUT' => 'warning',
                                                'DELETE' => 'danger',
                                                default => 'gray',
                                            })
                                            ->copyable(),

                                        TextEntry::make('ip')
                                            ->label(__('IP Address'))
                                            ->copyable(),

                                        TextEntry::make('user_agent')
                                            ->label(__('User Agent'))
                                            ->copyable(),
                                    ])
                                    ->columnSpanFull(),

                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('full_url')
                                            ->label(__('Full URL'))
                                            ->copyable(),

                                        TextEntry::make('referer')
                                            ->label(__('Source Referer'))
                                            ->copyable(),
                                    ])
                                    ->columnSpanFull(),
                            ])
                            ->columns(1),

                        Section::make(__('Route Details'))
                            ->description(__('Matched route and resolved parameters.'))
                            ->icon('heroicon-o-arrow-path')
                            ->schema([
                                TextEntry::make('route_name')
                                    ->label(__('Route Name'))
                                    ->columnSpan(1),

                                TextEntry::make('route_action')
                                    ->label(__('Route Action'))
                                    ->columnSpan(1),

                                KeyValueEntry::make('route_parameters')
                                    ->label(__('Route Parameters'))
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                    ])
                    ->modalFooterActionsAlignment(Alignment::Center)
                    ->modalSubmitAction(fn(Action $action) => $action->visible(false))
                    ->modalCancelActionLabel(fn(): string => __('Close')),
            ]);
    }
}
