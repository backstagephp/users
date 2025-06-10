<?php

namespace Backstage\Filament\Users;

use Backstage\Filament\Users\Components\ToggleSubNavigationType;
use Backstage\Filament\Users\Http\Middleware\RedirectUnverifiedUsers;
use Backstage\Laravel\Users\Http\Middleware\DetectUserTraffic;
use Closure;
use Filament\Contracts\Plugin;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\View\PanelsRenderHook;
use Livewire\Livewire;

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
                config('backstage.users.resources.users', Resources\UserResource::class),
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

        if (config('backstage.users.record.can_toggle_sub_navigation', true)) {
            $this->initSubNavigationToggle($panel);
        }

        $panel->middleware($middleware);

        $panel->authGuard('web');

        $panel->passwordReset();

        $panel->authPasswordBroker('users');

        $panel->pages([
            config('backstage.users.pages.manage-api-tokens', Pages\ManageApiTokens::class),
        ]);

        $panel->userMenuItems([
            MenuItem::make('api_tokens')
                ->label(__('API Tokens'))
                ->visible(fn () => config('backstage.users.pages.manage-api-tokens', Pages\ManageApiTokens::class)::canAccess())
                ->icon('heroicon-o-document-text')
                ->url(fn () => config('backstage.users.pages.manage-api-tokens', Pages\ManageApiTokens::class)::getUrl()),
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

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

    protected function initSubNavigationToggle(Panel $panel)
    {
        $panel->renderHook(PanelsRenderHook::GLOBAL_SEARCH_AFTER, function () {
            return Livewire::mount(ToggleSubNavigationType::class, []);
        });
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
