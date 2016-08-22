<?php

namespace Jenky\LaravelPushNotification;

use Illuminate\Contracts\Queue\Queue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Sly\NotificationPusher\Adapter\AdapterInterface;
use Sly\NotificationPusher\Model\Device;
use Sly\NotificationPusher\Model\Message;
use Sly\NotificationPusher\Model\Push;
use Sly\NotificationPusher\PushManager;

class NotificationPusher
{
    use DispatchesJobs;

    /**
     * @var \Sly\NotificationPusher\PushManager
     */
    protected $manager;

    /**
     * @var \Sly\NotificationPusher\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @var mixed
     */
    protected $to;

    /**
     * Create new class instance.
     *
     * @param  array $config
     * @return void
     */
    public function __construct(AdapterInterface $adapter, $env)
    {
        $this->adapter = $adapter;
        $this->manager = new PushManager($env);
    }

    /**
     * Set the receivers.
     *
     * @param  mixed $to
     * @return $this
     */
    public function to($to)
    {
        $this->to = is_string($to) ? new Device($to) : $to;

        return $this;
    }

    /**
     * Send the message.
     *
     * @param  mixed $message
     * @param  array $options
     * @return $this
     */
    public function send($message, $options = [])
    {
        $this->setupPush($message, $options);
        $this->manager->push();

        return $this;
    }

    /**
     * Queue the message for sending.
     *
     * @param  mixed $message
     * @param  array $options
     * @param  string|null $connection
     * @param  string|null $queue
     * @return void
     */
    public function queue($message, $options = [], $connection = null, $queue = null)
    {
        $this->setupPush($message, $options);

        $this->dispatchesJob($connection, $queue);
    }

    /**
     * Queue the message for sending after (n) seconds on the given queue.
     *
     * @param  int $delay
     * @param  mixed $message
     * @param  array $options
     * @param  string|null $connection
     * @param  string|null $queue
     * @return void
     */
    public function later($delay, $message, $options = [], $connection = null, $queue = null)
    {
        $this->setupPush($message, $options);

        $this->dispatchesJob($connection, $queue, $delay);
    }

    /**
     * Push the job to the queue.
     *
     * @param  string|null $connection
     * @param  string|null $queue
     * @param  int $delay
     * @return void
     */
    protected function dispatchesJob($connection = null, $queue = null, $delay = 0)
    {
        $this->dispatch((new SendQueuePushNotification($this->manager))
            ->onConnection($connection)
            ->onQueue($queue)
            ->delay($delay));
    }

    /**
     * Setup the push with push manager.
     *
     * @param  string|\Sly\NotificationPusher\Model\Message $message
     * @param  array $options
     * @return $this
     */
    protected function setupPush($message, $options = [])
    {
        $push = new Push($this->adapter, $this->to, ($message instanceof Message) ? $message : new Message($message, $options));

        $this->manager->add($push);

        return $this;
    }

    /**
     * Get the push manager feedback.
     *
     * @return mixed
     */
    public function getFeedback()
    {
        return $this->manager->getFeedback($this->adapter);
    }

    /**
     * Get the push manager.
     *
     * @return Sly\NotificationPusher\PushManager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Get the push adapter.
     *
     * @return \Sly\NotificationPusher\Adapter\BaseAdapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }
}
