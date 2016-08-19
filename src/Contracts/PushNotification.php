<?php

namespace Jenky\LaravelPushNotification\Contracts;

interface PushNotification
{
    /**
     * Get the push driver.
     *
     * @param  array|string $option
     * @return mixed
     */
    public function driver($option);
}
