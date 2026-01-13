<?php

namespace Backstage\Filament\Users\Resources\UserResource\Pages;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Event;
use Filament\Resources\Pages\CreateRecord;
use Backstage\Laravel\Users\Events\Auth\UserCreated;
use Backstage\Filament\Users\Resources\UserResource\UserResource;

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
