<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Push Notification Drivers
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the push notification "drivers" for your
    | application as well as their adapter. You may even define multiple
    | drivers for the same push notification adapter.
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
    | Here you may define all of the push notification adapters that will
    | be used in "drivers" options to send the push message.
    |
    */

    'adapters' => [
        'apns' => Sly\NotificationPusher\Adapter\Apns::class,
        'gcm' => Sly\NotificationPusher\Adapter\Gcm::class,
        'fcm' => Jenky\LaravelPushNotification\Adapters\Fcm::class,
    ],

];
