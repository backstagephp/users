<?php

namespace Backstage\UserManagement\Commands;

use Illuminate\Console\Command;

class UserManagementCommand extends Command
{
    public $signature = 'user-management';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
