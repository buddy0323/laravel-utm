<?php

namespace Adzbuck\LaravelUTM;
use Closure;

class RequestMethods
{
    public function laravelUTM(): Closure
    {
        return fn() => app(RequestMixin::class);
    }
}
