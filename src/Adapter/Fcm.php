<?php

namespace Jenky\LaravelPushNotification\Adapter;

use Jenky\LaravelPushNotification\Fcm\Client;
use Jenky\LaravelPushNotification\Fcm\Message;
use Sly\NotificationPusher\Adapter\Gcm;
use Sly\NotificationPusher\Model\MessageInterface;
use ZendService\Google\Gcm\Client as BaseClient;

class Fcm extends Gcm
{
    /**
     * Get opened client.
     *
     * @param  \ZendService\Google\Gcm\Client $client
     * @return \App\Services\PushNotification\Fcm\Client
     */
    public function getOpenedClient(BaseClient $client)
    {
        return parent::getOpenedClient(new Client);
    }

    /**
     * Get service message from origin.
     *
     * @param  array $tokens
     * @param  \Sly\NotificationPusher\Model\MessageInterface $message
     * @return \App\Services\PushNotification\Fcm\Message
     */
    public function getServiceMessageFromOrigin(array $tokens, MessageInterface $message)
    {
        $data = $message->getOptions();
        $data['message'] = $message->getText();

        $serviceMessage = new Message();
        $serviceMessage->setRegistrationIds($tokens)
            ->setData($data)
            ->setCollapseKey($this->getParameter('collapseKey'))
            ->setRestrictedPackageName($this->getParameter('restrictedPackageName'))
            ->setDelayWhileIdle($this->getParameter('delayWhileIdle', false))
            ->setTimeToLive($this->getParameter('ttl', 600))
            ->setDryRun($this->getParameter('dryRun', false))
            ->setPriority($this->getParameter('priority'));

        return $serviceMessage;
    }

    /**
     * Feedback.
     *
     * @return array
     */
    public function getFeedback()
    {
        return $this->response->getResults();
    }
}
