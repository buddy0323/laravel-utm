<?php

namespace Adzbuck\LaravelUTM\Tests;

use Adzbuck\LaravelUTM\Helpers\Store;
use Illuminate\Http\Request;
use Adzbuck\LaravelUTM\Tests\TestCase;
use Adzbuck\LaravelUTM\ParameterTracker;
use Adzbuck\LaravelUTM\Sources\RequestParameter;

class ParameterTrackerTest extends TestCase
{
    public function testItCanGetTheTrackedParametersFromARequest()
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

        /** @var ParameterTracker $parameterTracker */
        $parameterTracker = app(ParameterTracker::class);
        $parameterTracker->handle();

        $this->assertEquals(
            [
                'utm_source' => 'https://google.com/',
            ],
            Store::get(config('laravel-utm.first_touch_store_key'))
        );
    }

    public function testItReturnsWhenTrackingDisabledRequest()
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

        config()->set('laravel-utm.first_touch_store_key', false);
        config()->set('laravel-utm.last_touch_store_key', false);

        /** @var ParameterTracker $parameterTracker */
        $parameterTracker = app(ParameterTracker::class);
        $parameterTracker->handle();

        $this->assertNull(
            session()->get(config('laravel-utm.first_touch_store_key'))
        );
    }

    public function testItReturnsWhenNoParamsFromARequest()
    {
        /** @var ParameterTracker $parameterTracker */
        $parameterTracker = app(ParameterTracker::class);
        $parameterTracker->handle();

        $this->assertNull(
            session()->get(config('laravel-utm.first_touch_store_key'))
        );
    }

    public function testItCanGetCustomConfiguredTrackedParametersFromARequest()
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

        /** @var ParameterTracker $parameterTracker */
        $parameterTracker = app(ParameterTracker::class);
        $parameterTracker->handle();

        $this->assertEquals(
            [
                'custom_tracked' => 'https://google.com/',
            ],
            Store::get(config('laravel-utm.first_touch_store_key'))
        );
    }

    public function testItCanTrackTheRefererHeader()
    {
        app()->bind(
            Request::class,
            function () {
                $request = new Request();
                $request->headers->add(['Referer' => 'spatie.be']);

                return $request;
            }
        );

        /** @var ParameterTracker $parameterTracker */
        $parameterTracker = app(ParameterTracker::class);
        $parameterTracker->handle();

        $this->assertEquals(
            [
                'referer' => 'spatie.be',
            ],
            Store::get(config('laravel-utm.first_touch_store_key'))
        );
    }
}
