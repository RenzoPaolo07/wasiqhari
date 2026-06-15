<?php

namespace App\Http\Middleware;

use Closure;

class DisableCsrfForIot
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}