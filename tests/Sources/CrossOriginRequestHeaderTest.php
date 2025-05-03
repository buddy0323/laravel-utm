<?php

namespace Adzbuck\LaravelUTM\Tests\Sources;

use Illuminate\Http\Request;
use Adzbuck\LaravelUTM\Tests\TestCase;
use Adzbuck\LaravelUTM\Sources\CrossOriginRequestHeader;

class CrossOriginRequestHeaderTest extends TestCase
{
    public function testItCanGetARequestHeaderIfTheRequestWasCrossOrigin()
    {
        $request = new Request();
        $request->headers->set('Foo', 'bar');
        $request->headers->set('Referer', 'https://cross-origin-domain.com/');

        $this->assertEquals('bar', (new CrossOriginRequestHeader($request))->get('foo'));
    }

    public function testItCantGetARequestHeaderIfTheRequestWasNotCrossOrigin()
    {
        $request = new Request();
        $request->headers->set('Foo', 'bar');
        $request->headers->set('HOST', 'spatie.be');
        $request->headers->set('Referer', 'https://spatie.be/');

        $this->assertNull((new CrossOriginRequestHeader($request))->get('foo'));
    }
}
