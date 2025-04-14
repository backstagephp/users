<?php

namespace Backstage\UserManagement\Concerns;

use Backstage\UserManagement\Concerns;
use Illuminate\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Vormkracht10\Fields\Concerns\HasFields;
use Illuminate\Auth\Passwords\CanResetPassword;
use Laravel\Sanctum\HasApiTokens;

trait HasBackstageManagement
{
    use Concerns\Conditionals\HasConditionals;
    use Concerns\Relations\HasRelations;

    // Scopes
    use Concerns\Scopes\HasScopes;

    // Illuminate contracts:
    use Notifiable;
    use CanResetPassword;
    use MustVerifyEmail;

    // Backstage contracts:
    use HasFields;

    // Spatie roles
    use HasRoles;

    // Sanctum
    use HasApiTokens;
}
