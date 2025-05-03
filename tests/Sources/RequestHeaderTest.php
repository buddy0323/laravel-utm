<?php

namespace Adzbuck\LaravelUTM\Tests\Sources;

use Illuminate\Http\Request;
use Adzbuck\LaravelUTM\Tests\TestCase;
use Adzbuck\LaravelUTM\Sources\RequestHeader;

class RequestHeaderTest extends TestCase
{
    public function testItCanGetARequestHeader()
    {
        $request = new Request();
        $request->headers->set('Foo', 'bar');

        $this->assertEquals('bar', (new RequestHeader($request))->get('foo'));
    }
}
