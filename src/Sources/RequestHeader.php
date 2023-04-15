<?php

namespace Adzbuck\LaravelUTM\Sources;

use Illuminate\Http\Request;

class RequestHeader
{
    public function __construct(protected Request $request)
    {
    }

    public function get(string $key): array|string|null
    {
        return $this->request->header($key);
    }
}
