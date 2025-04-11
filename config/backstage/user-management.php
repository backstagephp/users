<?php

// config for Backstage/UserManagement

use Backstage\UserManagement\Models\UserLogin;

return [
    'eloquent' => [
        'users' => [
            'model' => \App\Models\User::class,
            'observer' => \Backstage\UserManagement\Observers\UserObserver::class
        ],

        'user_logins' => [
            'model' => UserLogin::class,
            'table' => 'user_logins',
        ],
    ],

    'record' => [
        'user_logins' => true,
        'user_traffic' => true,
    ],
];
