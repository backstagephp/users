<?php

namespace Backstage\Users\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Backstage\Users\Users
 */
class Users extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Backstage\Users\Users::class;
    }
}
