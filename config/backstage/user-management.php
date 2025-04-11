<?php

// config for Backstage/UserManagement

use Backstage\UserManagement\Models\UserLogin;

return [
    'eloquent' => [
        'users' => [
            'model' => \App\Models\User::class,
        ],

        'user_logins' => [
            'model' => UserLogin::class,
            'table' => 'user_logins',
            'primary_key' => 'id',
        ],
    ],

    'record' => [
        'user_logins' => true,
        'user_traffic' => true,
    ]
];
