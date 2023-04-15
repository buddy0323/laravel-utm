<?php

namespace Adzbuck\LaravelUTM\Helpers;

use Illuminate\Http\Request as IlluminateRequest;

class Request
{
    public static function isCrossOrigin(IlluminateRequest $request): bool
    {
        $refererHost = Url::host($request->header('referer') ?? '');

        return $refererHost !== $request->getHost();
    }
}
