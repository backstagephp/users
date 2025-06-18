<?php

namespace Backstage\Filament\Users;

use Backstage\Filament\Users\Components\ToggleSubNavigationType;
use Backstage\Filament\Users\Models\User;
use Backstage\Filament\Users\Testing\TestsUsers;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use SplFileInfo;

class UsersServiceProvider extends PackageServiceProvider
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
                    ->askToStarRepoOnGitHub('backstage/users');

                // Execute install:api command
                $command->endWith(function () use ($command) {
                    $command->call('install:api', [
                        '--force' => true,
                    ]);

                    $command->call('vendor:publish', [
                        '--provider' => 'Backstage\Laravel\Users\LaravelUsersServiceProvider',
                    ]);

                    $command->call('vendor:publish', [
                        '--provider' => 'Backstage\Filament\Users\UsersServiceProvider',
                    ]);

                    $command->call('vendor:publish', [
                        '--provider' => 'Spatie\Permission\PermissionServiceProvider',
                    ]);

                    $command->call('vendor:publish', [
                        '--tag' => 'filament-actions-migrations',
                    ]);

                    $command->call('migrate');
                });
            });

        if (file_exists($package->basePath('/../config/backstage/users.php'))) {
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
        Testable::mixin(new TestsUsers);

        Livewire::component('backstage.users::toggle-sub-navigation-type', ToggleSubNavigationType::class);
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
            // Css::make('user-management-styles', __DIR__ . '/../resources/dist/user-management.css'),
            // Js::make('user-management-scripts', __DIR__ . '/../resources/dist/user-management.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [];
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
        $migrationPath = __DIR__ . '/../database/migrations/';

        $files = File::allFiles($migrationPath);

        $migrations = collect($files)
            ->map(fn (SplFileInfo $splFile) => str($splFile->getBasename())->before('.')->toString())
            ->toArray();

        return [
            ...$migrations,
        ];
    }
}
