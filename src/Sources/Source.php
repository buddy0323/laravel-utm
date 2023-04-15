<?php

namespace Adzbuck\LaravelUTM\Sources;

use Illuminate\Http\Request;

interface Source
{
    public function __construct(Request $request);

    public function get(string $key): ?string;
}
