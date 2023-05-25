<?php

namespace Adzbuck\LaravelUTM;

class RouteMethods
{
    public function utmTracking()
    {
        return function () {
            $this->get('login', fn() => '')->name('laravel-utm');
        };
    }
}
