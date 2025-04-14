<?php

namespace Backstage\UserManagement\Listeners;

use Illuminate\Auth\Events\Logout;

class UserLogout
{
    public function handle(Logout $event)
    {
        /**
         * @var \Backstage\UserManagement\Concerns\HasBackstageManagement $user
         */
        $user = $event->user;

        $inputs = request()->except('_method', '_token', 'password');

        $user->logins()->create([
            'user_id' => $user->id,
            'type' => 'logout',
            'url' => request()->url(),
            'referrer' => request()->server('HTTP_REFERER'),
            'inputs' => count($inputs) ? json_encode($inputs) : null,
            'user_agent' => request()->server('HTTP_USER_AGENT'),
            'ip_address' => request()->ip(),
            'hostname' => gethostbyaddr(request()->ip()),
        ]);
    }
}
