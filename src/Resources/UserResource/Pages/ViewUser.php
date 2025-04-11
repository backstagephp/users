<?php

namespace Backstage\UserManagement\Resources\UserResource\Pages;

use Backstage\UserManagement\Resources\UserResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Notifications\Auth\VerifyEmail;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('send_verify_user_email')
                ->visible(fn ($record) => $record->isVerified() === false)
                ->label(__('Send Verification Email'))
                ->action(function ($record) {
                    $notification = new VerifyEmail;
                    $notification->url = Filament::getVerifyEmailUrl($record);
                    $record->notify($notification);
                }),

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
}
