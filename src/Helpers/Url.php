<?php

namespace Adzbuck\LaravelUTM\Helpers;

class Url
{
    public static function host(string $url): ?string
    {
        return parse_url($url, PHP_URL_HOST);
    }

    public static function addParameters(string $url, array $parameters = []): string
    {
        if (! $parameters) {
            return $url;
        }

        $parsedUrl = parse_url($url);
        parse_str($parsedUrl['query'] ?? '', $parsedQuery);

        $queryArr = array_merge(
            $parsedQuery,
            $parameters
        );

        $parsedUrl['query'] = http_build_query($queryArr);

        return static::unparseUrl($parsedUrl);
    }

    public static function unparseUrl(array $parsed): string
    {
        $url = '';

        if ($parsed['host'] ?? false) {
            $url .= $parsed['scheme'] ? $parsed['scheme'].'://' : '//';

            if ($parsed['user'] ?? false) {
                $url .= $parsed['user'];

                if ($parsed['pass'] ?? false) {
                    $url .= ':'.$parsed['pass'];
                }

                $url .= '@';
            }

            $url .= $parsed['host'];

            if ($parsed['port'] ?? false) {
                $url .= ':'.$parsed['port'];
            }
        }

        $url .= $parsed['path'] ?? '';

        if ($parsed['query'] ?? false) {
            $url .= '?'.$parsed['query'];
        }

        if ($parsed['fragment'] ?? false) {
            $url .= '#'.$parsed['fragment'];
        }

        return $url;
    }
}
