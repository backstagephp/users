<?php

namespace Backstage\Filament\Users\Listeners;

use Backstage\Filament\Users\Events\UserCreated;
use Backstage\Filament\Users\Notifications\Invitation;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendInvitationMail
//  implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(UserCreated $event)
    {
        $event->user->notify(new Invitation);
    }
}
