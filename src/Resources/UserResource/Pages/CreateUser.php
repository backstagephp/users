<?php

namespace Backstage\Filament\Users\Resources\UserResource\Pages;

use Backstage\Filament\Users\Resources\UserResource\UserResource;
use Backstage\Laravel\Users\Events\Auth\UserCreated;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Event;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public function beforeCreate(): void
    {
        Event::forget(UserCreated::class);
    }
}
