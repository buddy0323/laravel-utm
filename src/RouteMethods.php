<?php

namespace Adzbuck\LaravelUTM;

/**
 * @mixin \Illuminate\Routing\Router
 */
class RouteMethods
{
    public function utmTracking()
    {
        return function () {
            $this->get('login', fn() => '')->name('laravel-utm');
        };
    }
}
