<?php

namespace Jenky\LaravelPushNotification;

use Illuminate\Support\ServiceProvider;
use Jenky\LaravelPushNotification\Contracts\PushNotification;

class PushNotificationServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerApiHelper($this->app);
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $configPath = __DIR__.'/../config/push-notification.php';
        $this->publishes([$configPath => config_path('push-notification.php')], 'config');
        $this->mergeConfigFrom($configPath, 'push-notification');
    }

    /**
     * Register the helper class.
     *
     * @return void
     */
    protected function registerApiHelper()
    {
        $this->app->singleton(PushNotification::class, function ($app) {
            return new PushNotificationManager($app);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [PushNotification::class];
    }
}
