<?php

namespace NotificationChannels\AwsPinpoint\Exceptions;

use Exception;

class CouldNotSendNotification extends Exception
{
    public static function serviceRespondedWithAnError(Exception $exception): self
    {
        return new self(
            "AWS Pinpoint service error {$exception->getCode()}: {$exception->getMessage()}"
        );
    }
}
