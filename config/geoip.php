<?php

return [
    /*
    |--------------------------------------------------------------------------
    | GeoIP Driver Type
    |--------------------------------------------------------------------------
    |
    | Supported: "freegeoip", "ip-api", "maxmind", "telize"
    |
    */
    'driver' => env('GEOIP_DRIVER', 'maxmind'),

    /*
    |--------------------------------------------------------------------------
    | Return random ipaddresses (useful for dev envs)
    |--------------------------------------------------------------------------
    */
    'random' => env('GEOIP_RANDOM', false),

    /*
    |--------------------------------------------------------------------------
    | Free GeoIP Driver
    |--------------------------------------------------------------------------
    */
    'freegeoip' => [
        /*
        |--------------------------------------------------------------------------
        | Free GeoIP url
        |--------------------------------------------------------------------------
        |
        | Url to self hosted freegeoip (including port) without protocol
        |
        */
        'url' => env('GEOIP_FREEGEOIP_URL'),

        /*
        |--------------------------------------------------------------------------
        | Free GeoIP Secure connection
        |--------------------------------------------------------------------------
        |
        | Use http or https
        |
        */
        'secure' => env('GEOIP_FREEGEOIP_SECURE', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | IP-API Driver
    |--------------------------------------------------------------------------
    */
    'ip-api' => [
        /*
        |--------------------------------------------------------------------------
        | IP-API Pro Service Key
        |--------------------------------------------------------------------------
        |
        | Check out pro here: https://signup.ip-api.com/
        |
        */
        'key' => env('GEOIP_IPAPI_KEY'),

        /*
        |--------------------------------------------------------------------------
        | IP-API Secure connection
        |--------------------------------------------------------------------------
        |
        | Use http or https
        | Only applicable with the Pro service
        |
        */
        'secure' => env('GEOIP_IPAPI_SECURE', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maxmind Driver
    |--------------------------------------------------------------------------
    */
    'maxmind' => [
        /*
        |--------------------------------------------------------------------------
        | Maxmind Database
        |--------------------------------------------------------------------------
        |
        | Example: app_path().'/database/maxmind/GeoLite2-City.mmdb'
        |
        */
        'database' => base_path().'/'.env('GEOIP_MAXMIND_DATABASE', 'database/geoip/GeoLite2-City.mmdb'),

        /*
        |--------------------------------------------------------------------------
        | Maxmind Web Service Info
        |--------------------------------------------------------------------------
        */
        'user_id' => env('GEOIP_MAXMIND_USER_ID'),
        'license_key' => env('GEOIP_MAXMIND_LICENSE_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Telize Driver
    |--------------------------------------------------------------------------
    */
    'telize' => [
        /*
        |--------------------------------------------------------------------------
        | Telize Service Key
        |--------------------------------------------------------------------------
        |
        | Get your API key here: https://market.mashape.com/fcambus/telize
        |
        */

         'key' => env('GEOIP_TELIZE_KEY'),
    ],
];
