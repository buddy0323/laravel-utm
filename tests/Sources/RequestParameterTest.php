<?php

namespace Adzbuck\LaravelUTM\Tests\Sources;

use Illuminate\Http\Request;
use Adzbuck\LaravelUTM\Tests\TestCase;
use Adzbuck\LaravelUTM\Sources\RequestParameter;

class RequestParameterTest extends TestCase
{
    /** @test */
    public function it_can_get_a_request_parameter()
    {
        $request = new Request([
            'foo' => 'bar',
        ]);

        $this->assertEquals('bar', (new RequestParameter($request))->get('foo'));
    }
}
