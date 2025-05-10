<?php

namespace Backstage\Users\Concerns\Relations;

trait HasRelations
{
    use HasLoginsRelation;
    use HasNotificationPreferences;
    use HasTags;
    use HasTraffic;
}
