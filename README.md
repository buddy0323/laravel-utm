####This package is an updated fork of [spatie/laravel-utm-forwarder](https://github.com/spatie/laravel-utm-forwarder)
# Keeps track of UTMs and/or other parameters

[![Latest Version on Packagist](https://img.shields.io/packagist/v/adzbuck/laravel-utm.svg?style=flat-square)](https://packagist.org/packages/adzbuck/laravel-utm)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/adzbuck/laravel-utm/run-tests?label=Tests)](https://github.com/adzbuck/laravel-utm/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/adzbuck/laravel-utm.svg?style=flat-square)](https://packagist.org/packages/adzbuck/laravel-utm)

This package allows you to easily track first and last touch query parameters and headers via session. You can then easily access these parameters so you can add them to a form submission or a link to another domain you track.

## Installation

You can install the package via composer:

```bash
composer require adzbuck/laravel-utm
```

The package works via a middleware that needs to be added to the `web` stack in your `kernel.php` file. Make sure to register this middleware after the `StartSession` middleware.

```php
// app/Http/Kernel.php

protected $middlewareGroups = [
    'web' => [
        // ...
        \Illuminate\Session\Middleware\StartSession::class,
        // ...
        \Adzbuck\LaravelUTM\Middleware\ParameterTrackerMiddleware::class,
    ],
];
```

To configure the tracked parameters or how they're mapped on the URL parameters, you can publish the config file using:

```bash
php artisan vendor:publish --provider="Adzbuck\LaravelUTM\ServiceProvider"
```

This is the contents of the published config file:

```php
use Adzbuck\LaravelUTM\Sources;

return [
    /*
     * These are the analytics parameters that will be tracked when a user first visits
     * the application. The configuration consists of the parameter's key and the
     * source to extract this key from.
     *
     * Available sources can be found in the `\Adzbuck\LaravelUTM\Sources` namespace.
     */
    'tracked_parameters' => [
        [
            'key' => 'utm_source',
            'source' => Sources\RequestParameter::class,
        ],
        [
            'key' => 'utm_medium',
            'source' => Sources\RequestParameter::class,
        ],
        [
            'key' => 'utm_campaign',
            'source' => Sources\RequestParameter::class,
        ],
        [
            'key' => 'utm_term',
            'source' => Sources\RequestParameter::class,
        ],
        [
            'key' => 'utm_content',
            'source' => Sources\RequestParameter::class,
        ],
        [
            'key' => 'referer',
            'source' => Sources\CrossOriginRequestHeader::class,
        ],
    ],

    /**
     * We'll put the first touch tracked parameters in the session using this key.
     */
    'first_touch_session_key' => 'laravel_utm_parameters_first',

    /**
     * We'll put the last touch tracked parameters in the session using this key.
     */
    'last_touch_session_key' => 'laravel_utm_parameters_last',

    /**
     * If we should keep track of the first touch utm params
     */
    'first_touch' => true,

    /**
     * If we should keep track of the last touch utm params
     */
    'last_touch' => true,

    /*
     * When formatting an URL to add the tracked parameters we'll use the following
     * mapping to put tracked parameters in URL parameters.
     *
     * This is useful when using an analytics solution that ignores the utm_* parameters.
     */
    'parameter_url_mapping' => [
        'utm_source' => 'utm_source',
        'utm_medium' => 'utm_medium',
        'utm_campaign' => 'utm_campaign',
        'utm_term' => 'utm_term',
        'utm_content' => 'utm_content',
        'referer' => 'referer',
    ],
];
```

## Usage

There are three methods of tracking:

<dl>
  <dt>getFirstTouch</dt>
  <dd>This will get the parameters from the users first visit.</dd>

  <dt>getLastTouch</dt>
  <dd>This will get the parameters from the users last visit.</dd>

  <dt>getCurrent</dt>
  <dd>This will get the parameters from the current request.</dd>
</dl>

The easiest way to retrieve the tracked parameters is by resolving the `ParameterTracker` class:

```php
use Adzbuck\LaravelUTM\ParameterTracker;

// returns an array of the first touch tracked parameters
$parameterTracker = app(ParameterTracker::class)

$parameterTracker->getFirstTouch();
$parameterTracker->getLastTouch();
$parameterTracker->getCurrent();
```


You can also decorate an existing URL with the tracked parameters. This is useful to forward analytics to another domain you're running analytics on.

The example uses three requests:
```
First request:
https://mywebshop.com/?utm_source=facebook&utm_campaign=blogpost

2nd Request:
https://mywebshop.com/?utm_source=google&utm_campaign=blogpost

Current Request:
https://mywebshop.com/
```

##### decorateUrl/@trackedUrl
This does not use the  session tracking, it simply uses the params provided.
```blade
<?php
use Adzbuck\LaravelUTM\DecorateURL;
?>

<a href="{{ DecorateURL::decorateUrl('https://mywebshop.com/', ['utm_source' => 'google']) }}">
    Buy this product on our webshop
</a>

-- or --

<a href="@trackedUrl('https://mywebshop.com/', ['utm_source' => 'google'])">
    Buy this product on our webshop
</a>

Will link to https://mywebshop.com?utm_source=google
```

##### decorateUrlFromFirstTouch/@trackedUrlFromFirstTouch
This adds the parameters from the users first visit. You can also add extra params via an array as the second parameter.
```
<a href="{{ DecorateURL::decorateUrlFromFirstTouch('https://mywebshop.com/', ['extra_param' => 'test']) }}">
    Buy this product on our webshop
</a>

-- or --

<a href="@trackedUrlFromFirstTouch('https://mywebshop.com/', ['extra_param' => 'test'])">
    Buy this product on our webshop
</a>

Will link to https://mywebshop.com?utm_source=facebook&utm_campaign=blogpost&extra_param=test
```

##### decorateUrlFromLastTouch/@trackedUrlFromLastTouch
This adds the parameters from the users Last visit. You can also add extra params via an array as the second parameter.
```
<a href="{{ DecorateURL::decorateUrlFromLastTouch('https://mywebshop.com/', ['extra_param' => 'test']) }}">
    Buy this product on our webshop
</a>

-- or --

<a href="@trackedUrlFromLastTouch('https://mywebshop.com/', ['extra_param' => 'test'])">
    Buy this product on our webshop
</a>

Will link to https://mywebshop.com?utm_source=facebook&utm_campaign=blogpost&extra_param=test
```

##### decorateUrlFromCurrent/@trackedUrlFromCurrent
This adds the parameters from the users Last visit. You can also add extra params via an array as the second parameter.
```
<a href="{{ DecorateURL::decorateUrlFromCurrent('https://mywebshop.com/', ['extra_param' => 'test']) }}">
    Buy this product on our webshop
</a>

-- or --

<a href="@trackedUrlFromCurrent('https://mywebshop.com/', ['extra_param' => 'test'])">
    Buy this product on our webshop
</a>

Will link to https://mywebshop.com?utm_source=google&utm_campaign=blogpost&extra_param=test
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Alex Vanderbist](https://github.com/AlexVanderbist)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
