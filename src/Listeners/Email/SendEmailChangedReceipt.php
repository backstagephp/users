<?php

namespace Backstage\Filament\Users\Listeners\Email;

use Backstage\Laravel\Users\Events\Email\EmailChangeConfirmed;
use Backstage\Laravel\Users\Notifications\Email\EmailChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendEmailChangedReceipt implements ShouldQueue
{
    public function handle(EmailChangeConfirmed $event): void
    {
        $newEmail = (string) $event->user->email;
        $oldEmail = $event->oldEmail;

        Notification::route('mail', $newEmail)
            ->notify(new EmailChanged($oldEmail, $newEmail));

        if (! config('users.email_change.notify_old_address', true)) {
            return;
        }

        if ($oldEmail === '' || $oldEmail === $newEmail) {
            return;
        }

        Notification::route('mail', $oldEmail)
            ->notify(new EmailChanged($oldEmail, $newEmail));
    }
}
