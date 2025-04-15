<?php

namespace Backstage\UserManagement;

use Filament\Support\Assets\Js;
use Filament\Support\Assets\Css;
use Illuminate\Auth\Events\Login;
use Filament\Support\Assets\Asset;
use Illuminate\Auth\Events\Logout;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Event;
use Spatie\LaravelPackageTools\Package;
use Backstage\UserManagement\Models\User;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\AlpineComponent;
use Livewire\Features\SupportTesting\Testable;
use Backstage\UserManagement\Events\UserCreated;
use Backstage\UserManagement\Listeners\UserLogin;
use Spatie\Permission\Events as PermissionEvents;
use Backstage\UserManagement\Listeners\UserLogout;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Backstage\UserManagement\Events\WebTrafficDetected;
use Backstage\UserManagement\Listeners\SendWelcomeMail;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Backstage\UserManagement\Testing\TestsUserManagement;
use Backstage\UserManagement\Listeners\SendInvitationMail;
use Backstage\UserManagement\Listeners\RecordUserMovements;
use Backstage\UserManagement\Commands\UserManagementCommand;
use Backstage\UserManagement\Listeners\Permissions\LogRoleAttached;
use Backstage\UserManagement\Listeners\Permissions\LogRoleDetached;

class UserManagementServiceProvider extends PackageServiceProvider
{
    public static string $name = 'backstage:users';

    public static string $viewNamespace = 'backstage/users';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->setDescription('Install the User Management package')
                    ->setName(static::$name . ':install')
                    ->setDescription('Install the User Management package')
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('backstage/user-management');

                // Execute install:api command
                $command->endWith(function () use ($command) {
                    $command->call('install:api', [
                        '--force' => true,
                    ]);

                    $command->call('vendor:publish', [
                        '--tag' => 'permission-migrations',
                    ]);

                    $command->call('vendor:publish', [
                        '--tag' => 'permission-config',
                    ]);

                    $command->call('vendor:publish', [
                        '--tag' => 'filament-actions-migrations'
                    ]);

                    $command->call('migrate');
                });
            });

        if (file_exists($package->basePath("/../config/backstage/users.php"))) {
            $package->hasConfigFile('backstage/users');


            if (file_exists($package->basePath('/../database/migrations'))) {
                $package->hasMigrations($this->getMigrations());
            }

            if (file_exists($package->basePath('/../resources/lang'))) {
                $package->hasTranslations();
            }

            if (file_exists($package->basePath('/../resources/views'))) {
                $package->hasViews(static::$viewNamespace);
            }
        }
    }
    
    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/user-management/{$file->getFilename()}"),
                ], 'user-management-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsUserManagement);

        // User management
        Event::listen(Login::class, UserLogin::class);
        Event::listen(Logout::class, UserLogout::class);
        Event::listen(WebTrafficDetected::class, RecordUserMovements::class);

        if (config('permission.events_enabled')) {
            Event::listen(PermissionEvents\RoleDetached::class, LogRoleDetached::class);
            Event::listen(PermissionEvents\RoleAttached::class, LogRoleAttached::class);
            Event::listen(PermissionEvents\PermissionAttached::class);
            Event::listen(PermissionEvents\PermissionDetached::class);
        }

        Event::listen(UserCreated::class, SendInvitationMail::class);

        config(
            'backstage.user-management.users.model',
            User::class
        )::observe(
            config('backstage.users.eloquent.users.observer', \Backstage\UserManagement\Observers\UserObserver::class)
        );
    }

    protected function getAssetPackageName(): ?string
    {
        return 'backstage/user-management';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('user-management', __DIR__ . '/../resources/dist/components/user-management.js'),
            Css::make('user-management-styles', __DIR__ . '/../resources/dist/user-management.css'),
            // Js::make('user-management-scripts', __DIR__ . '/../resources/dist/user-management.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            UserManagementCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [
            'web',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_user_logins_table',
            'create_user_traffic_table',
            'create_permission_event_logs_table',
            'create_users_tags_pivot_table',
            'create_users_tags_table',
            'user_password_nullable',
            'add_sub_navigation_preference_to_users_table'
        ];
    }
}
