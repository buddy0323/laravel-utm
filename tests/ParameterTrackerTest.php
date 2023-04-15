<?php

namespace Adzbuck\LaravelUTM\Tests;

use Illuminate\Http\Request;
use Adzbuck\LaravelUTM\Tests\TestCase;
use Adzbuck\LaravelUTM\ParameterTracker;
use Adzbuck\LaravelUTM\Sources\RequestParameter;

class ParameterTrackerTest extends TestCase
{
    /** @test */
    public function it_can_get_the_tracked_parameters_from_a_request()
    {
        app()->bind(
            Request::class,
            function () {
                return new Request([
                    'irrelevant' => 'value',
                    'utm_source' => 'https://google.com/',
                ]);
            }
        );

        /** @var ParameterTracker */
        $app = app(ParameterTracker::class);
        $app->handle();

        $this->assertEquals(
            [
                'utm_source' => 'https://google.com/',
            ],
            session()->get(config('laravel-utm.first_touch_session_key'))
        );
    }

    /** @test */
    public function it_returns_when_tracking_disabled_request()
    {
        app()->bind(
            Request::class,
            function () {
                return new Request([
                    'irrelevant' => 'value',
                    'utm_source' => 'https://google.com/',
                ]);
            }
        );

        config()->set('laravel-utm.first_touch_session_key', false);
        config()->set('laravel-utm.last_touch_session_key', false);

        /** @var ParameterTracker */
        $app = app(ParameterTracker::class);
        $app->handle();

        $this->assertNull(
            session()->get(config('laravel-utm.first_touch_session_key'))
        );
    }

    /** @test */
    public function it_returns_when_no_params_from_a_request()
    {
        /** @var ParameterTracker */
        $app = app(ParameterTracker::class);
        $app->handle();

        $this->assertNull(
            session()->get(config('laravel-utm.first_touch_session_key'))
        );
    }

    /** @test */
    public function it_can_get_custom_configured_tracked_parameters_from_a_request()
    {
        app()->bind(
            Request::class,
            function () {
                return new Request([
                    'irrelevant' => 'value',
                    'custom_tracked' => 'https://google.com/',
                ]);
            }
        );

        config()->set('laravel-utm.tracked_parameters', [
            [
                'key' => 'custom_tracked',
                'source' => RequestParameter::class,
            ],
        ]);

        /** @var ParameterTracker */
        $app = app(ParameterTracker::class);
        $app->handle();

        $this->assertEquals(
            [
                'custom_tracked' => 'https://google.com/',
            ],
            session()->get(config('laravel-utm.first_touch_session_key'))
        );
    }

    /** @test */
    public function it_can_track_the_referer_header()
    {
        app()->bind(
            Request::class,
            function () {
                $request = new Request();
                $request->headers->add(['Referer' => 'spatie.be']);

                return $request;
            }
        );

        /** @var ParameterTracker */
        $app = app(ParameterTracker::class);
        $app->handle();

        $this->assertEquals(
            [
                'referer' => 'spatie.be',
            ],
            session()->get(config('laravel-utm.first_touch_session_key'))
        );
    }
}
