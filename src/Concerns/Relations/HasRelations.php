<?php

namespace Backstage\Users\Concerns\Relations;

trait HasRelations
{
    use HasLoginsRelation;
    use HasTags;
    use HasTraffic;
}
