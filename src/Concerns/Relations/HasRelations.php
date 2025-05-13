<?php

namespace Backstage\Filament\Users\Concerns\Relations;

trait HasRelations
{
    use HasLoginsRelation;
    use HasTags;
    use HasTraffic;
}
