<?php

namespace Backstage\Filament\Users\Pages\Email;

use Backstage\Filament\Users\Models\User;
use Backstage\Laravel\Users\Domain\Email\Actions\ConfirmEmailChange;
use Backstage\Laravel\Users\Domain\Email\Exceptions\EmailChangeException;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Panel;

class ConfirmEmailChangePage extends Page
{
    protected static ?string $slug = 'email/confirm/{user}/{token}';

    protected string $view = 'filament-panels::components.layout.simple';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function getRouteMiddleware(Panel $panel): string|array
    {
        return ['signed'];
    }

    public function mount(int|string $user, string $token): mixed
    {
        /** @var User|null $userModel */
        $userModel = config('auth.providers.users.model', User::class)::query()->find($user);

        if ($userModel === null) {
            abort(404);
        }

        $panelId = $userModel->pending_email_panel ?? Filament::getCurrentPanel()?->getId();

        try {
            ConfirmEmailChange::run($userModel, $token);

            $userModel->forceFill(['pending_email_panel' => null])->save();

            Notification::make()
                ->title(__('Your email address has been updated.'))
                ->success()
                ->send();
        } catch (EmailChangeException $exception) {
            Notification::make()
                ->title(__('We could not update your email address.'))
                ->body($exception->getMessage())
                ->danger()
                ->send();
        }

        return redirect($this->resolvePanelUrl($panelId));
    }

    protected function resolvePanelUrl(?string $panelId): string
    {
        if ($panelId === null) {
            return url('/');
        }

        try {
            return Filament::getPanel($panelId)->getUrl() ?? url('/');
        } catch (\Throwable) {
            return url('/');
        }
    }
}
