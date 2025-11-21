<?php

namespace Backstage\Filament\Users\Resources\UserResource\Pages;

use Backstage\Filament\Users\Actions\GenerateSignedRegistrationUri;
use Backstage\Filament\Users\Models\User;
use Backstage\Filament\Users\Resources\UserResource\UserResource;
use Filament\Actions;
use Filament\Auth\Notifications\ResetPassword;
use Filament\Auth\Notifications\VerifyEmail;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class ViewUser extends ViewRecord
{
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
                ->visible(fn (User $record): bool => $record->userIsRegistered() === false)
                ->requiresConfirmation()
                ->modalDescription(__('This action will open the registration link for this user. By confirming, you will be redirected to the registration page and logged out of your current session.')),

            Actions\ActionGroup::make([
                Actions\Action::make('send_verify_user_email')
                    ->visible(fn (User $record): bool => $record->hasVerifiedEmail() === false)
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
}
