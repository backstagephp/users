<?php

namespace Backstage\Filament\Users\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;

class RedirectUnverifiedUsers
{
    public function handle(Request $request, Closure $next)
    {
        if (Filament::auth()->check() && ! Filament::auth()->user()->hasVerifiedEmail() && $request->url() !== Filament::getEmailVerificationPromptUrl() && ! $request->routeIs('filament.' . Filament::getCurrentPanel()->getId() . '.auth.email-verification.verify')) {
            return redirect(Filament::getEmailVerificationPromptUrl());
        }

        return $next($request);
    }
}
