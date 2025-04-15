<?php

namespace Backstage\UserManagement\Listeners;

use Backstage\UserManagement\Events\UserCreated;
use Backstage\UserManagement\Notifications\Invitation;
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
