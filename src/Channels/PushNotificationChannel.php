<?php

namespace JenKy\LaravelPushNotification\Channels;

use Illuminate\Notifications\Notification;
use Jenky\LaravelPushNotification\Contracts\PushNotification;

class PushNotificationChannel
{
    /**
     * @var \Jenky\LaravelPushNotification\Contracts\PushNotification
     */
    protected $pushNotification;

    /**
     * Create new push notification channel instance.
     *
     * @param  \Jenky\LaravelPushNotification\Contracts\PushNotification $pushNotification
     * @return void
     */
    public function __construct(PushNotification $pushNotification)
    {
        $this->pushNotification = $pushNotification;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toPushMessage($notifiable);
        $to = $notifiable->routeNotificationFor('pushMessage');

        if (! $to || ! is_array($to)) {
            return;
        }

        list($driver, $token) = $to;
        $options = [];

        if (is_array($message)) {
            list($message, $options) = $message;
        }

        $this->pushNotification->driver($driver)
            ->to($token)
            ->send($message, $options);
    }
}
