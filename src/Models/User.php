<?php

namespace Backstage\Filament\Users\Models;

use Backstage\Filament\Users\Concerns\Conditionals\HasSubNavigationPreference;
use Backstage\Laravel\Users\Eloquent\Models\User as BaseUser;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends BaseUser implements FilamentUser
{
    use HasSubNavigationPreference;

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
