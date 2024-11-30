<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcast Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default broadcast driver that will be used to
    | broadcast events. Supported: "pusher", "redis", "log", "null".
    |
    */

    'default' => env('BROADCAST_DRIVER', 'pusher'),

    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    |
    | Here you may configure all of the broadcast connections for your application.
    | You can configure multiple broadcast drivers here, such as Pusher, Redis, or
    | a custom driver. By default, the "pusher" driver is configured for you.
    |
    */

    'connections' => [
        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                // 'use_tls' => env('BROADCAST_USE_TLS', true),
                'use_tls' => false,
                // 'encrypted' => true,
                'encrypted' => false,
                'authEndpoint' => env('PUSHER_APP_AUTH_ENDPOINT', '/broadcasting/auth'),
            ],
        ],

        // You can add other broadcast drivers here like 'redis', 'log', etc.
    ],

];
