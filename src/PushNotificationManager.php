<?php

namespace Jenky\LaravelPushNotification;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Queue\Queue;
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

        $pusher = new NotificationPusher($adapter, array_get($option, 'environment'));
        $pusher->setQueue($this->app[Queue::class]);

        return $pusher;
    }

    /**
     * Get the config data.
     *
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    protected function getConfig($key, $default = null)
    {
        return $this->app['config']->get('push-notification.'.$key, $default);
    }

    public function handleQueuedNotification($job, $data)
    {
        $job->delete();
    }
}
