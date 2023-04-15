<?php

namespace Adzbuck\LaravelUTM\Tests\Views\Directives;

use Illuminate\Http\Request;
use Adzbuck\LaravelUTM\Tests\TestCase;
use Illuminate\Support\Facades\Session;
use Adzbuck\LaravelUTM\DecorateURL;

class DecorateURLTest extends TestCase
{
    private static $host = 'https://localhost/';
    private static $firstTouchSource = 'https://laravel-news.com/';
    private static $lastTouchSource = 'https://laravel.com/';
    private static $currentSource = 'https://google.com/';

    protected function setUp(): void
    {
        parent::setUp();

        Session::put(
            config('laravel-utm.first_touch_session_key'),
            [
                'utm_source' => self::$firstTouchSource,
            ]
        );
        Session::put(
            config('laravel-utm.last_touch_session_key'),
            [
                'utm_source' => self::$lastTouchSource,
            ]
        );
        app()->bind(
            Request::class,
            function () {
                return new Request([
                    'irrelevant' => 'value',
                    'utm_source' => self::$currentSource,
                ]);
            }
        );
    }

    /** @test */
    public function it_can_format_an_url_without_tracked_parameters()
    {

        $formattedUrl = DecorateURL::decorateUrl(self::$host);

        $this->assertEquals(self::$host, $formattedUrl);
    }

    /** @test */
    public function it_can_format_an_url_tracked_parameters()
    {
        $formattedUrl = DecorateURL::decorateUrl(self::$host, ['utm_source' => self::$firstTouchSource]);

        $this->assertEquals(
            self::$host . '?' . http_build_query(['utm_source' => self::$firstTouchSource]),
            $formattedUrl
        );
    }

    /** @test */
    public function it_can_format_an_url_with_first_touch_tracked_parameters()
    {
        $formattedUrl = DecorateURL::decorateUrlFromFirstTouch(self::$host);

        $this->assertEquals(
            self::$host . '?' . http_build_query(['utm_source' => self::$firstTouchSource]),
            $formattedUrl
        );
    }

    /** @test */
    public function it_can_format_an_url_with_first_touch_tracked_parameters_and_extra_parameters()
    {
        $formattedUrl = DecorateURL::decorateUrlFromFirstTouch(self::$host, ['utm_term' => 'test']);

        $this->assertEquals(
            self::$host . '?' . http_build_query([
                'utm_source' => self::$firstTouchSource,
                'utm_term' => 'test',
            ]),
            $formattedUrl
        );
    }

    /** @test */
    public function it_can_format_an_url_with_authentication()
    {
        $formattedUrl = DecorateURL::decorateUrlFromFirstTouch(
            'ftp://testUser:testPass@localhost:123/path/path-two?utm_source=testing&utm_term=test#frag',
            []
        );

        $expectedParams = http_build_query([
            'utm_source' => self::$firstTouchSource,
            'utm_term' => 'test',
        ]);

        $this->assertEquals(
            'ftp://testUser:testPass@localhost:123/path/path-two?' . $expectedParams . '#frag',
            $formattedUrl
        );
    }

    /** @test */
    public function it_can_format_an_url_with_first_touch_tracked_parameters_and_override_parameters()
    {
        $formattedUrl = DecorateURL::decorateUrlFromFirstTouch(self::$host, ['utm_source' => 'test']);

        $this->assertEquals(
            self::$host . '?' . http_build_query([
                'utm_source' => 'test',
            ]),
            $formattedUrl
        );
    }

    /** @test */
    public function it_can_format_an_url_with_last_touch_tracked_parameters()
    {
        app()->bind(
            Request::class,
            function () {
                return new Request();
            }
        );

        $formattedUrl = DecorateURL::decorateUrlFromLastTouch(self::$host);

        $this->assertEquals(
            self::$host . '?' . http_build_query(['utm_source' => self::$lastTouchSource]),
            $formattedUrl
        );
    }

    /** @test */
    public function it_can_format_an_url_with_current_tracked_parameters()
    {
        $formattedUrl = DecorateURL::decorateUrlFromCurrent(self::$host);

        $this->assertEquals(
            self::$host . '?' . http_build_query(['utm_source' => self::$currentSource]),
            $formattedUrl
        );
    }

    /** @test */
    public function it_can_format_an_url_with_tracked_and_mapped_parameters()
    {
        config()->set('laravel-utm.parameter_url_mapping', [
            'utm_source' => 'custom_source',
        ]);

        $formattedUrl = DecorateURL::decorateUrlFromFirstTouch(self::$host);

        $this->assertEquals(
            self::$host . '?' . http_build_query(['custom_source' => self::$firstTouchSource]),
            $formattedUrl
        );
    }
}
