<?php

namespace Backstage\Filament\Users\Concerns\Conditionals;

use Filament\Pages\Enums\SubNavigationPosition;

trait HasSubNavigationPreference
{
    public function getSubNavigationPreference()
    {
        return match ($this->sub_navigation_preference) {
            'top' => SubNavigationPosition::Top,
            'start' => SubNavigationPosition::Start,
            'end' => SubNavigationPosition::End,
            default => null,
        };

        return '';
    }
}
