<?php

namespace Backstage\UserManagement\Concerns;

use Backstage\UserManagement\Concerns;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Notifications\Notifiable;
use Vormkracht10\Fields\Concerns\HasFields;

trait HasBackstageManagement
{
    use CanResetPassword;
    use CanResetPassword;
    use Concerns\Conditionals\HasConditionals;

    use Concerns\Relations\HasRelations;

    // Scopes
    use Concerns\Scopes\HasScopes;
    // Backstage contracts:
    use HasFields;

    // Illuminate contracts:
    use Notifiable;
}
