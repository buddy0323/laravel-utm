<?php

namespace Adzbuck\LaravelUTM\Tests\Middleware;

use Illuminate\Http\Request;
use Adzbuck\LaravelUTM\Tests\TestCase;
use Adzbuck\LaravelUTM\ParameterTracker;
use Adzbuck\LaravelUTM\Middleware\ParameterTrackerMiddleware;

class ParameterTrackerMiddlewareTest extends TestCase
{
    /** @test */
    public function it_tries_to_add_any_analytics_parameters_to_the_analytics_bag()
    {
        $request = new Request();

        $this->mock(ParameterTracker::class)
            ->expects('handle')
            ->once();

        /** @var ParameterTrackerMiddleware */
        $middleware = app(ParameterTrackerMiddleware::class);
        $middleware->handle($request, fn (Request $request) => $request);
    }
}
