<?php

namespace Adzbuck\LaravelUTM\Tests\Sources;

use Illuminate\Http\Request;
use Adzbuck\LaravelUTM\Tests\TestCase;
use Adzbuck\LaravelUTM\Sources\CrossOriginRequestParameter;

class CrossOriginRequestParameterTest extends TestCase
{
    public function testItCanGetARequestParameterIfTheRequestWasCrossOrigin()
    {
        $request = new Request([
            'foo' => 'bar',
        ]);
        $request->headers->set('Referer', 'https://cross-origin-domain.com/');

        $this->assertEquals('bar', (new CrossOriginRequestParameter($request))->get('foo'));
    }

    public function testItCantGetARequestParameterIfTheRequestWasNotCrossOrigin()
    {
        $request = new Request([
            'foo' => 'bar',
        ]);
        $request->headers->set('HOST', 'spatie.be');
        $request->headers->set('Referer', 'https://spatie.be/');

        $this->assertNull((new CrossOriginRequestParameter($request))->get('foo'));
    }
}
