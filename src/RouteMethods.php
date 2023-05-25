<?php

namespace Adzbuck\LaravelUTM;
use Closure;

/**
 * @mixin \Illuminate\Routing\Router
 */
class RouteMethods
{
    public function laravelUTM(): Closure
    {
        return function () {
            $this->get('utm', fn() => '')->name('laravel-utm');
        };
    }
}
