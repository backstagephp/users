<?php

namespace Backstage\Filament\Users\Resources\UserResource\Pages;

use Backstage\Filament\Users\Resources\UserResource\UserResource;
use Backstage\Laravel\Users\Events\Auth\UserCreated;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Event;

class CreateUser extends CreateRecord
{
    public static function getResource(): string
    {
        return config('backstage.users.resources.users', UserResource::class);
    }

    public function beforeCreate(): void
    {
        Event::forget(UserCreated::class);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return __('User created successfully, the user will receive an email with their registeration details.');
    }
}
