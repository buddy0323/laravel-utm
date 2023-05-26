<?php

namespace Adzbuck\LaravelUTM;

use Adzbuck\LaravelUTM\Helpers\Url;
use Adzbuck\LaravelUTM\ParameterTracker;

class DecorateURL
{
    public static function decorateUrl(string $url, array $params = []): string
    {
        return self::decorateUrlFromParams($url, $params);
    }

    public static function decorateUrlFromFirstTouch(string $url, array $params = []): string
    {
        /** @var ParameterTracker $parameterTracker */
        $parameterTracker = app(ParameterTracker::class);

        $analyticsParameters = array_merge(
            $parameterTracker->getFirstTouch(),
            $params
        );

        return self::decorateUrlFromParams($url, $analyticsParameters);
    }

    public static function decorateUrlFromLastTouch(string $url, array $params = []): string
    {
        /** @var ParameterTracker $parameterTracker */
        $parameterTracker = app(ParameterTracker::class);

        $analyticsParameters = array_merge(
            $parameterTracker->getLastTouch(),
            $params
        );

        return self::decorateUrlFromParams($url, $analyticsParameters);
    }

    public static function decorateUrlFromCurrent(string $url, array $params = []): string
    {
        /** @var ParameterTracker $parameterTracker */
        $parameterTracker = app(ParameterTracker::class);

        $analyticsParameters = array_merge(
            $parameterTracker->getCurrent(),
            $params
        );

        return self::decorateUrlFromParams($url, $analyticsParameters);
    }

    protected static function decorateUrlFromParams(string $url, array $analyticsParameters): string
    {
        $analyticsParameters = self::mapParametersToUrlParameters($analyticsParameters);

        return Url::addParameters($url, $analyticsParameters);
    }

    protected static function mapParametersToUrlParameters(array $parameters): array
    {
        $mapping = config('laravel-utm.parameter_url_mapping');

        return collect($parameters)
            ->mapWithKeys(
                fn (string $value, string $parameter) => [$mapping[$parameter] ?? $parameter => $value]
            )
            ->toArray();
    }
}
