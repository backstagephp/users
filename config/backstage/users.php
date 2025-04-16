<?php

// config for Backstage/Users

use Backstage\Users\Models;
use Backstage\Users\Resources\UserResource;
use Backstage\Users\Resources\UsersTagResource;

return [
    'resources' => [
        'users' => UserResource::class,
        'users-tags' => UsersTagResource::class,
    ],

    'eloquent' => [
        'users' => [
            'model' => Models\User::class,
            'table' => 'users',
            'observer' => \Backstage\Users\Observers\UserObserver::class,
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
