<?php

namespace Adzbuck\LaravelUTM;

use Illuminate\Support\Facades\Facade;

class LaravelUTM extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'LaravelUTM';
    }

    public static function routes(): void
    {
        static::$app->make('router')->laravelUTM();
    }
}
