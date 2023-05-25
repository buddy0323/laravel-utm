<?php

namespace Adzbuck\LaravelUTM\Middleware;

use Adzbuck\LaravelUTM\ParameterTracker;
use Closure;
use Illuminate\Http\Request;

class ParameterTrackerMiddleware
{
    public function __construct(protected ParameterTracker $parameterTracker)
    {
    }

    public function handle(Request $request, Closure $next): Request
    {
        $this->parameterTracker->handle();

        return $next($request);
    }
}
