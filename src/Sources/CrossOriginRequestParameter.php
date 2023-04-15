<?php

namespace Adzbuck\LaravelUTM\Sources;

use Adzbuck\LaravelUTM\Helpers\Request;

class CrossOriginRequestParameter extends RequestParameter
{
    public function get(string $key): ?string
    {
        if (! Request::isCrossOrigin($this->request)) {
            return null;
        }

        return parent::get($key);
    }
}
