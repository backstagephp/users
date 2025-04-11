<?php

namespace Backstage\UserManagement;

use Filament\Panel;
use Filament\Contracts\Plugin;
use Backstage\UserManagement\Resources;
use Backstage\UserManagement\Http\Middleware\DetectUserTraffic;

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
        ])
            ->middleware([
                DetectUserTraffic::class
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
}
