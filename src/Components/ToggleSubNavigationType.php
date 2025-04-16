<?php

namespace Backstage\UserManagement\Components;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class ToggleSubNavigationType extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public $icon = 'heroicon-o-adjustments-horizontal';

    public $current;

    public function render()
    {
        $this->current = $this->getCurrentSubNavigationType();

        return view('backstage.users::components.toggle-sub-navigation-type');
    }

    public function getCurrentSubNavigationType()
    {
        return Filament::auth()->user()->sub_navigation_preference;
    }

    public function toggleSubNavigationType()
    {
        dd();
    }
}
