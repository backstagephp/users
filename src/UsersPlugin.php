<?php

namespace Backstage\Filament\Users;

use Closure;
use BackedEnum;
use Filament\Panel;
use Livewire\Livewire;
use Filament\Actions\Action;
use Filament\Contracts\Plugin;
use Filament\Facades\Filament;
use Filament\Actions\ActionGroup;
use Filament\Support\Enums\Width;
use Filament\View\PanelsRenderHook;
use Filament\Support\Icons\Heroicon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Backstage\Filament\Users\Models\User;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Concerns\EvaluatesClosures;
use Livewire\Features\SupportRedirects\Redirector;
use Backstage\Filament\Users\Plugin\Actions\ToggleWidthAction;
use Backstage\Laravel\Users\Http\Middleware\DetectUserTraffic;
use Backstage\Filament\Users\Components\ToggleSubNavigationType;
use Backstage\Filament\Users\Http\Middleware\RedirectUnverifiedUsers;
use Backstage\Filament\Users\Plugin\Actions\ToggleSubnavigationTypeAction;

class UsersPlugin implements Plugin
{
    use EvaluatesClosures;

    public Closure | bool $canManageUsers = true;

    public function getId(): string
    {
        return 'users';
    }

    public function register(Panel $panel): void
    {
        if (config('backstage.users.resources.users') !== null) {
            $panel->resources([
                config('backstage.users.resources.users', Resources\UserResource\UserResource::class),
                config('backstage.users.resources.roles', Resources\RoleResource\RoleResource::class),
            ]);
        }

        $middleware = [];

        $panel->emailVerification();

        $panel->requiresEmailVerification();

        $panel->emailVerificationRoutePrefix('email-verification');
        $panel->emailVerificationPromptRouteSlug('prompt');
        $panel->emailVerificationRouteSlug('verify');

        $middleware[] = RedirectUnverifiedUsers::class;

        if (config('users.events.requests.web_traffic.enabled', true)) {
            $middleware[] = DetectUserTraffic::class;
        }

        $panel->middleware($middleware);

        $panel->authGuard('web');

        $panel->passwordReset();

        $panel->authPasswordBroker('users');

        $panel->pages([
            config('backstage.users.pages.manage-api-tokens', Pages\ManageApiTokens::class),
        ]);

        $panel->userMenuItems([
            Action::make('api_tokens')
                ->label(fn(): string => __('API Tokens'))
                ->visible(fn(): bool => config('backstage.users.pages.manage-api-tokens', Pages\ManageApiTokens::class)::canAccess())
                ->icon(fn(): BackedEnum => Heroicon::OutlinedDocumentText)
                ->url(fn(): string => config('backstage.users.pages.manage-api-tokens', Pages\ManageApiTokens::class)::getUrl()),

            ToggleWidthAction::make(),

            ToggleSubnavigationTypeAction::make(),
        ]);

        Filament::serving(function () {
            /**
             * @var User $user
             */
            $user = Filament::auth()->user();

            /**
             * @var Width $widthPreference
             */
            $widthPreference = $user?->getWidthPreference() ?? Width::SevenExtraLarge;

            tap(Filament::getCurrentPanel(), function (Panel $currentPanel) use ($widthPreference) {
                $currentPanel->maxContentWidth($widthPreference);
            });
        });
    }

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function canManageUsers(bool | Closure $condition = true): static
    {
        $this->canManageUsers = $condition;

        return $this;
    }

    public function canManageUsersCondition(): bool
    {
        return $this->evaluate($this->canManageUsers);
    }
}
