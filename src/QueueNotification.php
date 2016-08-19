<?php

namespace Jenky\LaravelPushNotification;

class QueueNotification
{
    /**
     * @var \Jenky\LaravelPushNotification\NotificationPusher
     */
    protected $pusher;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(NotificationPusher $pusher)
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
        dd($this->pusher);
        $this->pusher->getManager()->push();

        return $this->pusher;
    }
}
