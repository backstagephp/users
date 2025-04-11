<?php

namespace Backstage\UserManagement\Http\Middleware;

use Backstage\UserManagement\Events\WebTrafficDetected;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;

class DetectUserTraffic
{
    public function handle(Request $request, Closure $next)
    {
        if (Filament::auth()->check()) {
            event(new WebTrafficDetected($request));
        }

        return $next($request);
    }
}
