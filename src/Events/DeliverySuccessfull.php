<?php

namespace NotificationChannels\AwsPinpoint\Events;

class DeliverySuccessfull
{
    public mixed $recipient;

    public mixed $deliveryStatus;

    public mixed $statusMessage;

    /**
     * Create a new event instance
     */
    public function __construct(mixed $recipient, mixed $deliveryStatus, mixed $statusMessage)
    {
        $this->recipient = $recipient;
        $this->deliveryStatus = $deliveryStatus;
        $this->statusMessage = $statusMessage;
    }
}
