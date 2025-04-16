<?php

namespace Backstage\UserManagement\Concerns\Conditionals;

trait HasConditionals
{
    use UserIsVerified;
    use HasSubNavigationPreference;
}
