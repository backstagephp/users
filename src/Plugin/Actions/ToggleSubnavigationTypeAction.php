<?php

namespace Backstage\Filament\Users\Plugin\Actions;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Http\RedirectResponse;
use Backstage\Filament\Users\Models\User;
use Filament\Pages\Enums\SubNavigationPosition;
use Livewire\Features\SupportRedirects\Redirector;

class ToggleSubnavigationTypeAction extends Action
{
    protected function setUp(): void
    {
        $this->visible(fn(): bool => config('backstage.users.record.can_toggle_sub_navigation', true));

        $this->label(fn(): string => __('Toggle Sub Navigation Type'));

        $this->icon(fn(): BackedEnum => Heroicon::OutlinedAdjustmentsHorizontal);

        $this->action(function (): RedirectResponse|Redirector {
            /**
             * @var User $user
             */
            $user = Filament::auth()->user();

            /**
             * @var SubNavigationPosition $current
             */
            $currentPosition = $user->getSubNavigationPreference();

            /**
             * @var SubNavigationPosition $current
             */
            if ($currentPosition === SubNavigationPosition::Top) {
                $currentPosition = SubNavigationPosition::End;
            } elseif ($currentPosition === SubNavigationPosition::End) {
                $currentPosition = SubNavigationPosition::Top;
            }

            $user->update(['sub_navigation_preference' => $currentPosition->value]);

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
        return 'toggle_sub_navigation_type';
    }
}
