<?php

namespace Backstage\Filament\Users\Console\Commands;

use Filament\Facades\Filament;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\note;
use function Laravel\Prompts\warning;

class UpgradeCommand extends Command
{
    protected $signature = 'backstage-users:upgrade
        {--force : Skip confirmation prompts}
        {--no-migrate : Skip running migrations}';

    protected $description = 'Upgrade backstage/users: run pending migrations and verify the panel-aware email change flow.';

    public function handle(): int
    {
        info('Upgrading backstage/users…');

        if (! $this->option('force') && ! confirm(
            label: 'This will run pending migrations for backstage/laravel-users and backstage/users, then verify the email change flow. Continue?',
            default: true,
        )) {
            warning('Upgrade cancelled.');

            return self::SUCCESS;
        }

        $laravelUsersResult = $this->call('users:upgrade', [
            '--force' => true,
            '--no-migrate' => true,
        ]);

        if ($laravelUsersResult !== self::SUCCESS) {
            warning('backstage/laravel-users upgrade reported issues. Aborting.');

            return self::FAILURE;
        }

        if (! $this->option('no-migrate')) {
            $this->call('migrate', ['--force' => true]);
        }

        $this->verifyEmailChangeSetup();

        info('backstage/users is up to date.');

        return self::SUCCESS;
    }

    protected function verifyEmailChangeSetup(): void
    {
        $usersTable = config('users.eloquent.user.table', 'users');

        $missing = collect([
            'pending_email',
            'pending_email_token',
            'pending_email_token_expires_at',
            'pending_email_requested_at',
            'pending_email_panel',
        ])->reject(fn (string $column) => Schema::hasColumn($usersTable, $column));

        if ($missing->isNotEmpty()) {
            warning(__('The following columns are missing on :table — run migrations to enable the panel-aware email change flow: :columns', [
                'table' => $usersTable,
                'columns' => $missing->implode(', '),
            ]));

            return;
        }

        info(__('Panel-aware email change flow is wired up.'));

        $panels = collect(Filament::getPanels())
            ->map(fn ($panel) => $panel->getId())
            ->values();

        if ($panels->isEmpty()) {
            note(__('No Filament panels detected — register the UsersPlugin on a panel for the flow to take effect.'));

            return;
        }

        note(__('Detected Filament panels: :panels. Confirmation links will be scoped per panel.', [
            'panels' => $panels->implode(', '),
        ]));

        if (! config('users.email_change.enabled', true)) {
            warning(__('Email change is disabled (users.email_change.enabled = false). Set it to true to activate the flow.'));
        }
    }
}
