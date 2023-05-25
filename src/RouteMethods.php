<?php

namespace Adzbuck\LaravelUTM;

/**
 * @mixin \Illuminate\Routing\Route
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
