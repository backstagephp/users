<?php

namespace Backstage\UserManagement;

use Backstage\UserManagement\Commands\UserManagementCommand;
use Backstage\UserManagement\Events\WebTrafficDetected;
use Backstage\UserManagement\Listneners\RecordUserMovements;
use Backstage\UserManagement\Listneners\UserLogin;
use Backstage\UserManagement\Listneners\UserLogout;
use Backstage\UserManagement\Testing\TestsUserManagement;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Event;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class UserManagementServiceProvider extends PackageServiceProvider
{
    public static string $name = 'user-management';

    public static string $viewNamespace = 'user-management';

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
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('backstage/user-management');
            });

        $configFileName = 'backstage/' . $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile($configFileName);
        }

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
        return [];
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
        ];
    }
}
