<?php

namespace Backstage\Filament\Users\Concerns\Resources;

use Filament\Facades\Filament;
use Filament\Pages\SubNavigationPosition;

trait HasSubNavigationPosition
{
    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return Filament::auth()->user()->getSubNavigationPreference();
    }
}
