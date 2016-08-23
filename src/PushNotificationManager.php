<?php

namespace Jenky\LaravelPushNotification;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Str;
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
     * @param  array|string $options
     * @return \Jenky\LaravelPushNotification\NotificationPusher
     */
    public function driver($options)
    {
        $options = is_array($options) ? $options : $this->getConfig('drivers.'.$options, []);
        $adapterClass = $this->getConfig('adapters.'.array_get($options, 'adapter'));

        if (! $adapterClass || ! class_exists($adapterClass)) {
            throw new PushNotificationException('No adapter found');
        }

        $adapter = new $adapterClass($this->getAdapterOptions($options));

        return new NotificationPusher($adapter, array_get($options, 'environment'));
    }

    /**
     * Get the adapter options.
     *
     * @param  array $option
     * @return array
     */
    protected function getAdapterOptions(array $options)
    {
        $options = array_except($options, ['environment', 'adapter']);
        if (! empty($options['certificate'])) {
            $options['certificate'] = $this->getPath($options['certificate']);
        }

        return $options;
    }

    /**
     * Parse the full path with path helper function.
     *
     * @param  string $path
     * @return string
     */
    protected function getPath($path)
    {
        if (Str::contains($path, ':')) {
            // Such as resource_path:certs/mypem.pem or storage_path:certs/mypem.pem etc.
            list($function, $path) = explode(':', $path);
            if (! empty($function) && ! empty($path)) {
                return $function($path);
            }
        }

        return $path;
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
