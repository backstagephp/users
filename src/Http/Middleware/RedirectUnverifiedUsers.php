<?php

namespace Backstage\UserManagement\Http\Middleware;

use Backstage\UserManagement\Events\WebTrafficDetected;
use Closure;
use Filament\Facades\Filament;
use Filament\Pages\Auth\EmailVerification\EmailVerificationPrompt;
use Illuminate\Http\Request;

class RedirectUnverifiedUsers
{
    public function handle(Request $request, Closure $next)
    {
        if (Filament::auth()->check() && !Filament::auth()->user()->hasVerifiedEmail() && $request->url() !== Filament::getEmailVerificationPromptUrl() && !$request->routeIs('filament.' . Filament::getCurrentPanel()->getId() . '.auth.email-verification.verify')) {
            return redirect(Filament::getEmailVerificationPromptUrl());
        }

        return $next($request);
    }
}
