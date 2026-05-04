<?php

namespace Backstage\Filament\Users\Listeners\Email;

use Backstage\Filament\Users\Pages\Email\CancelEmailChangePage;
use Backstage\Filament\Users\Pages\Email\ConfirmEmailChangePage;
use Backstage\Laravel\Users\Events\Email\EmailChangeInitiated;
use Backstage\Laravel\Users\Notifications\Email\ConfirmEmailChange;
use Backstage\Laravel\Users\Notifications\Email\EmailChangeRequested;
use Filament\Facades\Filament;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendPanelAwareEmailChangeNotifications implements ShouldQueue
{
    public function handle(EmailChangeInitiated $event): void
    {
        if ($event->source === null) {
            return;
        }

        $panelId = $event->source;

        if (! $this->panelExists($panelId)) {
            return;
        }

        $confirmUrl = ConfirmEmailChangePage::getUrl(
            ['user' => $event->user->getKey(), 'token' => $event->rawToken],
            panel: $panelId,
        );

        Notification::route('mail', $event->newEmail)
            ->notify(new ConfirmEmailChange($event->newEmail, $confirmUrl));

        if (! config('users.email_change.notify_old_address', true)) {
            return;
        }

        $cancelUrl = CancelEmailChangePage::getUrl(
            ['user' => $event->user->getKey(), 'token' => $event->rawToken],
            panel: $panelId,
        );

        $event->user->notify(new EmailChangeRequested($event->newEmail, $cancelUrl));
    }

    protected function panelExists(string $panelId): bool
    {
        try {
            Filament::getPanel($panelId);

            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
