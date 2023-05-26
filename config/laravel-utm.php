<?php
use Adzbuck\LaravelUTM\Sources;
use Adzbuck\LaravelUTM\StoreType;

return [
    /**
     * How the data will be stored
     */
    'store' => StoreType::Cookie,

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
     * The name of the cooke that will be set
     */
    'cookie_name' => 'utm_params',

    /**
     * We'll put the first touch tracked parameters in the session using this key.
     */
    'first_touch_store_key' => 'laravel_utm_parameters_first',

    /**
     * We'll put the last touch tracked parameters in the session using this key.
     */
    'last_touch_store_key' => 'laravel_utm_parameters_last',

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
