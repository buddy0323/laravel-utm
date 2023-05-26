<?php

namespace Adzbuck\LaravelUTM\Helpers;

use Adzbuck\LaravelUTM\StoreType;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class Store
{
    public static function set(
        array|string $key,
        mixed $value = null,
    ): void {
        /** @var StoreType */
        $storeType = config('laravel-utm.store');

        switch($storeType) {
            case StoreType::Session:
                Session::put($key, $value);

                return;
            case StoreType::Cookie:
            default:
                $cookieName = config('laravel-utm.cookie_name');
                $cookieData = self::getCookie() ?? [];
                $cookieData[$key] = $value;
                Cookie::queue($cookieName, json_encode($cookieData));

                return;
        }
    }

    public static function get(
        array|string $key,
        mixed $default = null,
    ): mixed {
        /** @var StoreType */
        $storeType = config('laravel-utm.store');
        switch($storeType) {
            case StoreType::Session:
                return Session::get($key, $default);
            case StoreType::Cookie:
            default:
                return self::getCookie()[$key] ?? $default;
        }
    }

    protected static function getCookie(): mixed
    {
        /** @var string */
        $cookieName = config('laravel-utm.cookie_name');
        $content = Cookie::get($cookieName) ?? Cookie::queued($cookieName)?->getValue();

        if (! $content) {
            return null;
        }

        return json_decode($content, true);
    }
}
