<?php

namespace Adzbuck\LaravelUTM;

use Illuminate\Support\Facades\Facade;

class LaravelUTM extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'LaravelUTM';
    }

    /**
     * Register the tracking route for an application.
     *
     * @return void
     */
    public static function routes(): void
    {
        static::$app->make('router')->laravelUTM();
    }
}
