<?php

namespace Backstage\Filament\Users\Concerns\Conditionals;

use Filament\Pages\Enums\SubNavigationPosition;

trait HasSubNavigationPreference
{
    public function getSubNavigationPreference()
    {
        $preference = $this->sub_navigation_preference ?? SubNavigationPosition::Top;

        return $preference;
    }
}
