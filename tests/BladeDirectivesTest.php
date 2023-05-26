<?php

namespace Adzbuck\LaravelUTM\Tests\Views\Directives;

use Adzbuck\LaravelUTM\Helpers\Store;
use Illuminate\Http\Request;
use Adzbuck\LaravelUTM\Tests\TestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;

class BladeDirectivesTest extends TestCase
{
    use InteractsWithViews;

    private static $host = 'https://localhost/';
    private static $firstTouchSource = 'https://laravel-news.com/';
    private static $lastTouchSource = 'https://laravel.com/';
    private static $currentSource = 'https://google.com/';

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

    /** @test */
    public function it_can_format_an_url()
    {
        $formattedUrl = $this->blade('@trackedUrl(\'' . self::$host . '\')');

        $this->assertEquals(self::$host, (string)$formattedUrl);
    }

    /** @test */
    public function it_can_format_an_url_with_extra_parameters()
    {
        $formattedUrl = $this->blade('@trackedUrl(\'' . self::$host . '\', [\'utm_source\' => \'test\' ])');

        $this->assertEquals(self::$host . '?utm_source=test', (string)$formattedUrl);
    }

    /** @test */
    public function it_can_format_an_url_from_first_touch_parameters()
    {
        $formattedUrl = $this->blade('@trackedUrlFromFirstTouch(\'' . self::$host . '\')');

        $this->assertEquals(
            self::$host . '?' . http_build_query([
                'utm_source' => self::$firstTouchSource,
            ]),
            (string)$formattedUrl
        );
    }

    /** @test */
    public function it_can_format_an_url_from_first_touch_parameters_with_extra_params()
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

    /** @test */
    public function it_can_format_an_url_from_last_touch_parameters()
    {
        $formattedUrl = $this->blade('@trackedUrlFromLastTouch(\'' . self::$host . '\')');

        $this->assertEquals(
            self::$host . '?' . http_build_query([
                'utm_source' => self::$lastTouchSource,
            ]),
            (string)$formattedUrl
        );
    }

    /** @test */
    public function it_can_format_an_url_from_last_touch_parameters_with_extra_params()
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

    /** @test */
    public function it_can_format_an_url_from_current_parameters()
    {
        $formattedUrl = $this->blade('@trackedUrlFromCurrent(\'' . self::$host . '\')');

        $this->assertEquals(
            self::$host . '?' . http_build_query([
                'utm_source' => self::$currentSource,
            ]),
            (string)$formattedUrl
        );
    }

    /** @test */
    public function it_can_format_an_url_from_current_parameters_with_extra_params()
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
