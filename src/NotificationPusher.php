<?php

namespace Jenky\LaravelPushNotification;

use Illuminate\Contracts\Queue\Queue;
use Sly\NotificationPusher\Adapter\AdapterInterface;
use Sly\NotificationPusher\Model\Device;
use Sly\NotificationPusher\Model\Message;
use Sly\NotificationPusher\Model\Push;
use Sly\NotificationPusher\PushManager;

class NotificationPusher
{
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
     * The queue implementation.
     *
     * @var \Illuminate\Contracts\Queue\Queue
     */
    protected $queue;

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
     * Queue the message.
     *
     * @param  mixed $message
     * @param  array $options
     * @param  string|null $queue
     * @return $this
     */
    public function queue($message, $options = [], $queue = null)
    {
        $this->setupPush($message, $options);

        if ($queue) {
            return $this->queue->pushOn(
                $queue, new QueueNotification($this)
            );
        } else {
            return $this->queue->push(
                Contracts\PushNotification::class.'@handleQueuedNotification', new QueueNotification($this)
            );
        }
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

    /**
     * Set the queue manager instance.
     *
     * @param  \Illuminate\Contracts\Queue\Queue $queue
     * @return $this
     */
    public function setQueue(Queue $queue)
    {
        $this->queue = $queue;

        return $this;
    }
}
