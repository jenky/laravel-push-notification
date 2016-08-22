<?php

namespace Jenky\LaravelPushNotification;

use Illuminate\Notifications\ChannelManager;
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
        $this->registerPushNotification();
        if (class_exists(ChannelManager::class) && $this->app[ChannelManager::class]) {
            $this->registerPushNotificationChannel();
        }
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
     * Register the push notification class.
     *
     * @return void
     */
    protected function registerPushNotification()
    {
        $this->app->singleton(PushNotification::class, function ($app) {
            return new PushNotificationManager($app);
        });
    }

    /**
     * Register push notification channel class.
     *
     * @return void
     */
    protected function registerPushNotificationChannel()
    {
        $this->app[ChannelManager::class]->extend('push_message', function ($app) {
            return new Channels\PushNotificationChannel($app[PushNotification::class]);
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
