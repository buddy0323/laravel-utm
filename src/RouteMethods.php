<?php

namespace Adzbuck\LaravelUTM;

class RouteMethods
{
    public function utmTracking()
    {
        return function () {
            /**
             * @psalm-suppress UndefinedMethod
             */
            $this->get('login', fn() => '')->name('laravel-utm');
        };
    }
}
