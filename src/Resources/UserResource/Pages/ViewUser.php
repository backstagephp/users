<?php

namespace Backstage\UserManagement\Resources\UserResource\Pages;

use Backstage\UserManagement\Resources\UserResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Notifications\Auth\ResetPassword;
use Filament\Notifications\Auth\VerifyEmail;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Vormkracht10\Fields\Concerns\CanMapDynamicFields;

class ViewUser extends ViewRecord
{
    use CanMapDynamicFields;

    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\Action::make('send_verify_user_email')
                    ->visible(fn ($record) => $record->isVerified() === false)
                    ->label(__('Send Verification Email'))
                    ->action(function ($record) {
                        $notification = new VerifyEmail;
                        $notification->url = Filament::getVerifyEmailUrl($record);
                        $record->notify($notification);
                    })
                    ->requiresConfirmation(),

                Actions\Action::make('send_password_reset_email')
                    ->label(__('Send Password Reset Email'))
                    ->action(function ($record) {
                        /**
                         * @var \Backstage\UserManagement\Concerns\HasBackstageManagement $user
                         * @var \Illuminate\Contracts\Auth\Authenticatable $user
                         */
                        $user = $this->record;
                        /**
                         * Broker
                         *
                         * @var \Illuminate\Auth\Passwords\PasswordBroker $broker
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
         * @var \Backstage\UserManagement\Concerns\HasBackstageManagement $user
         */
        $user = $this->record;

        $verified = $user->isVerified();

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

    public function getFormFields()
    {
        return $this->resolveFormFields();
    }
}
