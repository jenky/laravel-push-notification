<?php

namespace Jenky\LaravelPushNotification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Sly\NotificationPusher\PushManager;

class SendQueuePushNotification implements ShouldQueue
{
    use Queueable;

    /**
     * @var \Sly\NotificationPusher\PushManager
     */
    protected $pusher;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PushManager $pusher)
    {
        $this->pusher = $pusher;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->pusher->push();
    }
}
