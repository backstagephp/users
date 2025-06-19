<?php

namespace Backstage\Filament\Users\Actions;

use Illuminate\Support\Uri;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;
use Backstage\Filament\Users\Pages\RegisterFromInvitationPage;

class GenerateSignedRegistrationUri
{
    use AsAction;

    public function handle(Model $user): string
    {
        $dedicatedPanel = Filament::getPanel('register');

        $routename = RegisterFromInvitationPage::getRouteName($dedicatedPanel);

        $encryptedUserId = encrypt($user->getKey());

        $signedRouteUri = Uri::signedRoute($routename, [
            'userId' => $encryptedUserId,
        ]);

        $url = Uri::to($signedRouteUri)->__toString();

        return $url;
    }
}
