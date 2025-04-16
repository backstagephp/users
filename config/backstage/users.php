<?php

// config for Backstage/UserManagement

use Backstage\UserManagement\Models;
use Backstage\UserManagement\Resources\UserResource;
use Backstage\UserManagement\Resources\UsersTagResource;

return [
    'resources' => [
        'users' => UserResource::class,
        'users-tags' => UsersTagResource::class
    ],

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
        'user_must_verify' => true,
        'can_toggle_sub_navigation' => true,
    ],
];
