<?php

namespace Jenky\LaravelPushNotification;

use Illuminate\Contracts\Foundation\Application;
use Jenky\LaravelPushNotification\Contracts\PushNotification;

class PushNotificationManager implements PushNotification
{
    /**
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Create new class instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Get the push driver.
     *
     * @param  array|string $option
     * @return \Jenky\LaravelPushNotification\NotificationPusher
     */
    public function driver($option)
    {
        $option = is_array($option) ? $option : $this->getConfig('drivers.'.$option, []);
        $adapterClass = $this->getConfig('adapters.'.array_get($option, 'adapter'));

        if (! $adapterClass || ! class_exists($adapterClass)) {
            throw new PushNotificationException('No adapter found');
        }

        $adapter = new $adapterClass(array_except($option, ['environment', 'adapter']));

        return new NotificationPusher($adapter, array_get($option, 'environment'));
    }

    /**
     * Get the config value.
     *
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    protected function getConfig($key, $default = null)
    {
        return $this->app['config']->get('push-notification.'.$key, $default);
    }
}
