<?php

// config for Backstage/Users

use Backstage\Filament\Users\Pages\ManageApiTokens;
use Backstage\Filament\Users\Resources\RoleResource\RoleResource;
use Backstage\Filament\Users\Resources\UserResource\UserResource;

return [
    'resources' => [
        'users' => UserResource::class,
        'roles' => RoleResource::class,
    ],

    'pages' => [
        'manage-api-tokens' => ManageApiTokens::class,
    ],

    'record' => [
        'can_toggle_sub_navigation' => true,
        'can_toggle_width' => true,
        'manage-api-tokens' => false,
    ],
];
