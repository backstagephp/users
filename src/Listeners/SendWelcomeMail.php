<?php

namespace Backstage\UserManagement\Listeners;

use Backstage\UserManagement\Events\UserCreated;
use Backstage\UserManagement\Notifications\WelcomeEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWelcomeMail
//  implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  \Backstage\UserManagement\Events\UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        $event->user->notify(new WelcomeEmail);
    }
}
