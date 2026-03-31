<?php

namespace Backstage\Filament\Users\Resources\UserResource\Pages;

use Backstage\Filament\Users\Resources\UserResource\UserResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    public static function getResource(): string
    {
        return config('backstage.users.resources.users', UserResource::class);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return __('User created successfully, the user will receive an email with their registeration details.');
    }

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index', ['tenant' => Filament::getTenant()?->getRouteKey()]);
    }
}
