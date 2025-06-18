<?php

namespace Backstage\Filament\Users\Concerns\Resources;

use Filament\Facades\Filament;
use Filament\Pages\Enums\SubNavigationPosition;

trait HasSubNavigationPosition
{
    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        /**
         * @var \Backstage\Laravel\Users\Eloquent\Models\User $user
         */
        $user = Filament::auth()->user();

        return $user->getSubNavigationPreference();
    }
}
