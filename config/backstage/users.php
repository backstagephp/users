<?php

// config for Backstage/Users

use Backstage\Filament\Users\Models;
use Backstage\Filament\Users\Pages\ManageApiTokens;
use Backstage\Filament\Users\Resources\UserResource;
use Backstage\Filament\Users\Resources\UsersTagResource;

return [
    'resources' => [
        'users' => UserResource::class,
        'users-tags' => UsersTagResource::class,
    ],

    'pages' => [
        'manage-api-tokens' => ManageApiTokens::class,
    ],

    'eloquent' => [
        'users' => [
            'model' => Models\User::class,
            'table' => 'users',
            'observer' => \Backstage\Filament\Users\Observers\UserObserver::class,
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
        'manage-api-tokens' => false,
    ],
];
