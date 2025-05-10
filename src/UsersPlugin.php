<?php

namespace Backstage\Users;

use Backstage\Users\Components\ToggleSubNavigationType;
use Backstage\Users\Http\Middleware\DetectUserTraffic;
use Backstage\Users\Http\Middleware\RedirectUnverifiedUsers;
use Backstage\Users\Pages\Auth\Profile;
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

    public bool | Closure $profile = true;

    public bool | Closure $isSimple = true;

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

        if ($this->isProfileEnabled()) {
            $panel->profile(page: Profile::class, isSimple: $this->isSimpleProfile());
        }
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

    public function profile(bool | Closure $uses = true, bool | Closure $isSimple = true): self
    {
        $this->profile = $uses;

        $this->isSimple = $isSimple;

        return $this;
    }

    public function isProfileEnabled(): bool
    {
        return $this->evaluate($this->profile);
    }

    public function isSimpleProfile(): bool
    {
        return $this->evaluate($this->isSimple);
    }
}
