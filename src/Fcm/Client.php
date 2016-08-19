<?php

namespace Jenky\LaravelPushNotification\Fcm;

use ZendService\Google\Gcm\Client as BaseClient;

class Client extends BaseClient
{
    /**
     * @const string Server URI
     */
    const SERVER_URI = 'https://fcm.googleapis.com/fcm/send';
}
