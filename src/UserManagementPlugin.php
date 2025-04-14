<?php

namespace Backstage\UserManagement;

use Filament\Panel;
use Filament\Contracts\Plugin;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Backstage\UserManagement\Http\Middleware\DetectUserTraffic;
use Backstage\UserManagement\Http\Middleware\RedirectUnverifiedUsers;

class UserManagementPlugin implements Plugin
{
    public function getId(): string
    {
        return 'user-management';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            Resources\UserResource::class,
            Resources\UsersTagResource::class
        ]);

        $panel->middleware([
            DetectUserTraffic::class,

            RedirectUnverifiedUsers::class
        ]);

        $panel->emailVerification();

        $panel->requiresEmailVerification();

        $panel->emailVerificationRoutePrefix('email-verification');
        $panel->emailVerificationPromptRouteSlug('prompt');
        $panel->emailVerificationRouteSlug('verify');

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
}
