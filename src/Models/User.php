<?php

namespace Backstage\Filament\Users\Models;

use Backstage\Filament\Users\Concerns\Conditionals\HasSubNavigationPreference;
use Backstage\Filament\Users\Concerns\Conditionals\HasWidthPreference;
use Backstage\Filament\Users\Events\FilamentUserCreated;
use Backstage\Laravel\Users\Eloquent\Models\User as BaseUser;
use Filament\Models\Contracts\FilamentUser;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Panel;
use Filament\Support\Enums\Width;

class User extends BaseUser implements FilamentUser
{
    use HasSubNavigationPreference;
    use HasWidthPreference;

    public function getCasts(): array
    {
        return parent::getCasts() + [
            'width_preference' => Width::class,
            'sub_navigation_preference' => SubNavigationPosition::class,
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function (self $user) {
            event(new FilamentUserCreated($user));
        });
    }
}

