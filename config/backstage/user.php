<?php

// config for Backstage/UserManagement

use Backstage\UserManagement\Models;

return [
    'eloquent' => [
        'users' => [
            'model' => Models\User::class,
            'table' => 'users',
            'observer' => \Backstage\UserManagement\Observers\UserObserver::class,
        ],

        'user_logins' => [
            'model' => Models\UserLogin::class,
            'table' => 'user_logins',
        ],
    ],

    'record' => [
        'user_logins' => true,
        'user_traffic' => true,
    ],
];
