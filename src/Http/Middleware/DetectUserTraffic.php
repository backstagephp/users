<?php

namespace Backstage\UserManagement\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Backstage\UserManagement\Events\WebTrafficDetected;
use Filament\Facades\Filament;

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
