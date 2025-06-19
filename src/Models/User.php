<?php

namespace Backstage\Filament\Users\Models;

use Backstage\Filament\Users\Concerns\Conditionals\HasSubNavigationPreference;
use Backstage\Filament\Users\Events\FilamentUserCreated;
use Backstage\Laravel\Users\Eloquent\Models\User as BaseUser;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends BaseUser implements FilamentUser
{
    use HasSubNavigationPreference;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
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
