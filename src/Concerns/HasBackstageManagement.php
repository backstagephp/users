<?php

namespace Backstage\UserManagement\Concerns;

use Backstage\UserManagement\Concerns;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Notifications\Notifiable;

trait HasBackstageManagement
{
    use Concerns\Conditionals\HasConditionals;
    use Concerns\Relations\HasRelations;
    use Concerns\Scopes\HasScopes;

    // Illuminate contracts:
    use Notifiable;
    use CanResetPassword;
}
