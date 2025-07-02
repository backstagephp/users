<?php

// config for Backstage/Users

use Backstage\Filament\Users\Pages\ManageApiTokens;
use Backstage\Filament\Users\Resources\UserResource\Pages\ManageRoles;
use Backstage\Filament\Users\Resources\UserResource\UserResource;

return [
    'resources' => [
        'users' => UserResource::class,
    ],

    'resource-pages' => [
        'users' => [
            'roles' => [
                'route' => '/{record}/roles',
                'routeName' => 'manage-roles',
                'page' => ManageRoles::class,
                'registerSubNavigation' => true,
            ],
        ],
    ],

    'pages' => [
        'manage-api-tokens' => ManageApiTokens::class,
    ],

    'record' => [
        'can_toggle_sub_navigation' => true,
        'manage-api-tokens' => false,
    ],
];
