<?php

namespace Backstage\Filament\Users\Concerns\Conditionals;

use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Enums\Width;

trait HasWidthPreference
{
    public function getWidthPreference(): ?Width
    {
        $preference = $this->width_preference;

        return $preference;
    }
}
