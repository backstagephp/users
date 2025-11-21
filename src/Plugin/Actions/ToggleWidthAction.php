<?php

namespace Backstage\Filament\Users\Plugin\Actions;

use BackedEnum;
use Backstage\Filament\Users\Models\User;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class ToggleWidthAction extends Action
{
    protected function setUp(): void
    {
        $this->visible(fn (): bool => config('backstage.users.record.can_toggle_width', true));

        $this->label(fn (): string => __('Toggle width'));

        $this->icon(fn (): BackedEnum => Heroicon::OutlinedAdjustmentsHorizontal);

        $this->action(function (): RedirectResponse | Redirector {
            /**
             * @var User $user
             */
            $user = Filament::auth()->user();

            /**
             * @var Width $currentWidth
             */
            $currentWidth = $user->getWidthPreference();

            /**
             * @var Width $width
             */
            $width = $currentWidth === Width::Full ? Width::SevenExtraLarge : Width::Full;

            /**
             * @var User $user
             */
            $user = Filament::auth()->user();

            $user->update(['width_preference' => $width->value]);

            /**
             * @var string $referer
             */
            $referer = request()->header('referer', '/');

            /**
             * @var RedirectResponse $redirectTo
             */
            $redirectTo = redirect()->to($referer);

            return $redirectTo;
        });
    }

    public static function getDefaultName(): ?string
    {
        return 'toggle_width';
    }
}
