<?php

namespace Backstage\UserManagement\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Backstage\UserManagement\UserManagement
 */
class UserManagement extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Backstage\UserManagement\UserManagement::class;
    }
}
