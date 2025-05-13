<?php

namespace Backstage\Filament\Users\Events;

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
     * @var \Backstage\Filament\Users\
     */
    public function __construct(public $user) {}
}
