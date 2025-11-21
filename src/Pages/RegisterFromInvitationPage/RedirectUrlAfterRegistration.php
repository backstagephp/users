<?php

namespace Backstage\Filament\Users\Pages\RegisterFromInvitationPage;

use Closure;

class RedirectUrlAfterRegistration
{
    public static Closure $redirectUrl;

    public static function set(Closure $redirectUrl): void
    {
        self::$redirectUrl = $redirectUrl;
    }

    public static function get($user): ?string
    {
        if (isset(self::$redirectUrl)) {
            return self::evaluate(self::$redirectUrl, $user);
        }

        return null;
    }

    protected static function evaluate(Closure $closure, $user): ?string
    {
        return app()->call($closure, [
            'user' => $user,
        ]);
    }
}
