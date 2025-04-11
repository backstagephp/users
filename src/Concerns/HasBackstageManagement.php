<?php

namespace Backstage\UserManagement\Concerns;

use Backstage\UserManagement\Concerns;

trait HasBackstageManagement
{
    use Concerns\Conditionals\HasConditionals;
    use Concerns\Relations\HasRelations;
    use Concerns\Scopes\HasScopes;
}
