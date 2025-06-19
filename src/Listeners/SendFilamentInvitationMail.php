<?php

namespace Backstage\Filament\Users\Listeners;

use Backstage\Filament\Users\Events\FilamentUserCreated;
use Backstage\Filament\Users\Notifications\UserInvitationNotification;
use Backstage\Laravel\Users\Events\Auth\UserCreated;
use Backstage\Laravel\Users\Notifications\Invitation;

class SendFilamentInvitationMail
{
    public function handle(FilamentUserCreated $event)
    {
        $user = $event->getUser();

        $invitation = new UserInvitationNotification;

        $user->notify($invitation);
    }
}
