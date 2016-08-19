<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Push Notification Drivers
    |--------------------------------------------------------------------------
    |
    | Description
    |
    */

    'drivers' => [

        'ios' => [
            'environment' => 'production',
            'certificate' => env('APNS_CERTIFICATE_PATH'),
            'passPhrase' => null,
            'adapter' => 'apns',
        ],

        'android' => [
            'environment' => 'production',
            'apiKey' => env('FCM_API_KEY'),
            'adapter' => 'fcm',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Push Notification Adapters
    |--------------------------------------------------------------------------
    |
    | Description
    |
    */

    'adapters' => [
        'apns' => Sly\NotificationPusher\Adapter\Apns::class,
        'gcm' => Sly\NotificationPusher\Adapter\Gcm::class,
        'fcm' => Jenky\LaravelPushNotification\Adapter\Fcm::class,
    ],

];
