<?php

namespace Backstage\UserManagement\Concerns\Relations;

trait HasRelations
{
    use HasLoginsRelation;
    use HasTags;
    use HasTraffic;
}
