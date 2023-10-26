<?php

namespace NotificationChannels\AwsPinpoint\Exceptions;

use Exception;

class InvalidConfig extends Exception
{
    public static function configNotFound(): self
    {
        return new self('Config not found. Please add your credentials to config/services.php"');
    }
}
