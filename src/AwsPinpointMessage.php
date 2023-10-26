<?php

namespace NotificationChannels\AwsPinpoint;

class AwsPinpointMessage
{
    public string $body;

    public string $type;

    public mixed $recipients;

    public string $senderId;

    public const TRANSACTIONAL_SMS_TYPE = 'TRANSACTIONAL';

    public const PROMOTIONAL_SMS_TYPE = 'PROMOTIONAL';

    public function __construct(string $body = '')
    {
        $this->body = trim($body);
        $this->type = self::TRANSACTIONAL_SMS_TYPE;
    }

    public static function create(string $body = ''): self
    {
        return new self($body);
    }

    public function body(string $body): self
    {
        $this->body = trim($body);

        return $this;
    }

    public function transactional(): self
    {
        $this->type = self::TRANSACTIONAL_SMS_TYPE;

        return $this;
    }

    public function promotional(): self
    {
        $this->type = self::PROMOTIONAL_SMS_TYPE;

        return $this;
    }

    /**
     * Set the recipients to which the message will be sent
     */
    public function recipients(mixed $recipients): self
    {
        $recipients = (array) $recipients;

        $this->recipients = [];

        foreach ($recipients as $phoneNumber) {
            $this->recipients[$phoneNumber] = [
                'ChannelType' => 'SMS',
            ];
        }

        return $this;
    }
}
