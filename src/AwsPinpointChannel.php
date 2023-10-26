<?php

namespace NotificationChannels\AwsPinpoint;

use Illuminate\Notifications\Notification;

class AwsPinpointChannel
{
    protected AwsPinpointClient $client;

    public function __construct(AwsPinpointClient $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification
     *
     * @param  mixed  $notifiable
     */
    public function send($notifiable, Notification $notification): void
    {
        $message = $notification->toAwsPinpoint($notifiable);

        if (! ($message instanceof AwsPinpointMessage)) {
            return;
        }

        if (! isset($message->recipients)) {
            if (! method_exists($notifiable, 'routeNotificationForAwsPinpoint')) {
                return;
            }

            $recipients = $notifiable->routeNotificationForAwsPinpoint();

            $message->recipients($recipients);
        }

        $this->client->sendMessage($message);
    }
}
