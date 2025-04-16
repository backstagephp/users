<?php

namespace Backstage\Users\Listeners;

use Backstage\Users\Events\UserCreated;
use Backstage\Users\Notifications\Invitation;
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
