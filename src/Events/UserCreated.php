<?php

namespace Backstage\UserManagement\Events;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserCreated
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    /**
     * The user instance.
     *
     * @var \Backstage\UserManagement\
     */
    public function __construct(public $user) {}
}
