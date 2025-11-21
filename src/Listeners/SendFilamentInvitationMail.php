<?php

namespace Backstage\Filament\Users\Listeners;

use Backstage\Filament\Users\Events\FilamentUserCreated;
use Backstage\Filament\Users\Notifications\UserInvitationNotification;

class SendFilamentInvitationMail
{
    public function handle(FilamentUserCreated $event)
    {
        $user = $event->getUser();

        $invitation = new UserInvitationNotification;

        $user->notify($invitation);
    }
}
