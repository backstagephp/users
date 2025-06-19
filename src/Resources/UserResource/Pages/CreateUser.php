<?php

namespace Backstage\Filament\Users\Resources\UserResource\Pages;

use Illuminate\Support\Facades\Event;
use Filament\Resources\Pages\CreateRecord;
use Backstage\Laravel\Users\Events\Auth\UserCreated;
use Backstage\Laravel\Users\Listeners\Auth\SendInvitationMail;
use Backstage\Filament\Users\Resources\UserResource\UserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public function beforeCreate(): void
    {
        Event::forget(UserCreated::class);
    }
}
