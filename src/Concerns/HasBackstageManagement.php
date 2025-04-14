<?php

namespace Backstage\UserManagement\Concerns;

use Backstage\UserManagement\Concerns;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Vormkracht10\Fields\Concerns\HasFields;

trait HasBackstageManagement
{
    use CanResetPassword;
    use Concerns\Conditionals\HasConditionals;
    use Concerns\Relations\HasRelations;
    use Concerns\Scopes\HasScopes;

    // Sanctum
    use HasApiTokens;
    // Backstage contracts:
    use HasFields;

    // Spatie roles
    use HasRoles;

    use MustVerifyEmail;

    // Illuminate contracts:
    use Notifiable;
}
