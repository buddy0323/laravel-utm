<?php

namespace Adzbuck\LaravelUTM\Tests\Middleware;

use Illuminate\Http\Request;
use Adzbuck\LaravelUTM\Tests\TestCase;
use Adzbuck\LaravelUTM\ParameterTracker;
use Adzbuck\LaravelUTM\Middleware\ParameterTrackerMiddleware;

class ParameterTrackerMiddlewareTest extends TestCase
{
    public function testItTriesToAddAnyAnalyticsParametersToTheAnalyticsBag()
    {
        $request = new Request();

        $this->mock(ParameterTracker::class)
            ->expects('handle')
            ->once();

        /** @var ParameterTrackerMiddleware $middleware */
        $middleware = app(ParameterTrackerMiddleware::class);
        $middleware->handle($request, fn (Request $request) => $request);
    }
}
