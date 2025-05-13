<?php

namespace Backstage\Filament\Users\Http\Middleware;

use Backstage\Filament\Users\Events\WebTrafficDetected;
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
