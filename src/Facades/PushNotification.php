<?php

namespace Jenky\LaravelPushNotification\Facades;

use Illuminate\Support\Facades\Facade;
use Jenky\LaravelPushNotification\Contracts\PushNotification as PushNotificationContract;

class PushNotification extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return PushNotificationContract::class;
    }
}
