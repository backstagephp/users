<?php

namespace Backstage\Filament\Users\Events;

use Backstage\Filament\Users\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FilamentUserCreated
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    protected User $user;

    /**
     * The user instance.
     *
     * @var User
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the user instance.
     *
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
