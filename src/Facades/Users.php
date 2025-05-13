<?php

namespace Backstage\Filament\Users\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Backstage\Filament\Users\Users
 */
class Users extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Backstage\Filament\Users\Users::class;
    }
}
