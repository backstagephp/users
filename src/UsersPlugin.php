<?php

namespace Backstage\Users;

use Filament\Panel;
use Livewire\Livewire;
use Filament\Contracts\Plugin;
use Filament\View\PanelsRenderHook;
use Backstage\Users\Http\Middleware\DetectUserTraffic;
use Backstage\Users\Components\ToggleSubNavigationType;
use Backstage\Users\Http\Middleware\RedirectUnverifiedUsers;
use Backstage\Users\Resources;

class UsersPlugin implements Plugin
{
    public function getId(): string
    {
        return 'users';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            config('backstage.users.resources.users', Resources\UserResource::class),

            config('backstage.users.resources.users-tags', Resources\UsersTagResource::class),
        ]);

        $middleware = [];

        if (config('backstage.users.record.user_must_verify', false)) {
            $panel->emailVerification();

            $panel->requiresEmailVerification();

            $panel->emailVerificationRoutePrefix('email-verification');
            $panel->emailVerificationPromptRouteSlug('prompt');
            $panel->emailVerificationRouteSlug('verify');

            $middleware[] = RedirectUnverifiedUsers::class;
        }

        if (config('backstage.users.record.user_traffic', true)) {
            $middleware[] = DetectUserTraffic::class;
        }

        if (config('backstage.users.record.can_toggle_sub_navigation', true)) {
            $this->initSubNavigationToggle($panel);
        }

        $panel->middleware($middleware);

        $panel->authGuard('web');

        $panel->passwordReset();

        $panel->authPasswordBroker('users');
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
}
