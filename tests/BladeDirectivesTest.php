<?php

namespace Adzbuck\LaravelUTM\Tests\Views\Directives;

use Adzbuck\LaravelUTM\Helpers\Store;
use Illuminate\Http\Request;
use Adzbuck\LaravelUTM\Tests\TestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;

class BladeDirectivesTest extends TestCase
{
    use InteractsWithViews;

    private static string $host = 'https://localhost/';
    private static string $firstTouchSource = 'https://laravel-news.com/';
    private static string $lastTouchSource = 'https://laravel.com/';
    private static string $currentSource = 'https://google.com/';

    protected function setUp(): void
    {
        parent::setUp();

        Store::set(
            config('laravel-utm.first_touch_store_key'),
            [
                'utm_source' => self::$firstTouchSource,
            ]
        );
        Store::set(
            config('laravel-utm.last_touch_store_key'),
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

    public function testItCanFormatAnUrl()
    {
        $formattedUrl = $this->blade('@trackedUrl(\'' . self::$host . '\')');

        $this->assertEquals(self::$host, (string)$formattedUrl);
    }

    public function testItCanFormatAnUrlWithExtraParameters()
    {
        $formattedUrl = $this->blade('@trackedUrl(\'' . self::$host . '\', [\'utm_source\' => \'test\' ])');

        $this->assertEquals(self::$host . '?utm_source=test', (string)$formattedUrl);
    }

    public function testItCanFormatAnUrlFromFirstTouchParameters()
    {
        $formattedUrl = $this->blade('@trackedUrlFromFirstTouch(\'' . self::$host . '\')');

        $this->assertEquals(
            self::$host . '?' . http_build_query([
                'utm_source' => self::$firstTouchSource,
            ]),
            (string)$formattedUrl
        );
    }

    public function testItCanFormatAnUrlFromFirstTouchParametersWithExtraParams()
    {
        $formattedUrl = $this->blade('@trackedUrlFromFirstTouch(\'' . self::$host . '\', [\'utm_term\' => \'test\'])');

        $this->assertEquals(
            self::$host . '?' . http_build_query([
                'utm_source' => self::$firstTouchSource,
                'utm_term' => 'test',
            ]),
            (string)$formattedUrl
        );
    }

    public function testItCanFormatAnUrlFromLastTouchParameters()
    {
        $formattedUrl = $this->blade('@trackedUrlFromLastTouch(\'' . self::$host . '\')');

        $this->assertEquals(
            self::$host . '?' . http_build_query([
                'utm_source' => self::$lastTouchSource,
            ]),
            (string)$formattedUrl
        );
    }

    public function testItCanFormatAnUrlFromLastTouchParametersWithExtraParams()
    {
        $formattedUrl = $this->blade('@trackedUrlFromLastTouch(\'' . self::$host . '\', [\'utm_term\' => \'test\'])');

        $this->assertEquals(
            self::$host . '?' . http_build_query([
                'utm_source' => self::$lastTouchSource,
                'utm_term' => 'test',
            ]),
            (string)$formattedUrl
        );
    }

    public function testItCanFormatAnUrlFromCurrentParameters()
    {
        $formattedUrl = $this->blade('@trackedUrlFromCurrent(\'' . self::$host . '\')');

        $this->assertEquals(
            self::$host . '?' . http_build_query([
                'utm_source' => self::$currentSource,
            ]),
            (string)$formattedUrl
        );
    }

    public function testItCanFormatAnUrlFromCurrentParametersWithExtraParams()
    {
        $formattedUrl = $this->blade('@trackedUrlFromCurrent(\'' . self::$host . '\', [\'utm_term\' => \'test\'])');

        $this->assertEquals(
            self::$host . '?' . http_build_query([
                'utm_source' => self::$currentSource,
                'utm_term' => 'test',
            ]),
            (string)$formattedUrl
        );
    }
}
