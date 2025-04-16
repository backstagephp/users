<?php

namespace Backstage\Users\Concerns\Conditionals;

use Filament\Pages\SubNavigationPosition;

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

    public function isSubNavigationPreference($type)
    {
        return $this->getSubNavigationPreference() === $type;
    }
}
