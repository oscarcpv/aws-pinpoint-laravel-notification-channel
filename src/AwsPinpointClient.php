<?php

namespace NotificationChannels\AwsPinpoint;

use Aws\Pinpoint\PinpointClient;
use Exception;
use NotificationChannels\AwsPinpoint\Events\DeliveryFailed;
use NotificationChannels\AwsPinpoint\Events\DeliverySuccessfull;
use NotificationChannels\AwsPinpoint\Exceptions\CouldNotSendNotification;

class AwsPinpointClient
{
    protected PinpointClient $client;

    public function __construct(PinpointClient $client)
    {
        $this->client = $client;
    }

    /**
     * Send SMS message
     */
    public function sendMessage(AwsPinpointMessage $message): void
    {
        try {
            $result = $this->client->sendMessages([
                'ApplicationId' => config('services.aws_pinpoint.application_id'),
                'MessageRequest' => [
                    'Addresses' => $message->recipients,
                    'MessageConfiguration' => [
                        'SMSMessage' => [
                            'Body' => $message->body,
                            'MessageType' => $message->type,
                            'SenderId' => config('services.aws_pinpoint.sms.sender_id'),
                        ],
                    ],
                ],
            ]);

            $response = $result->get('MessageResponse');

            foreach ($response['Result'] as $phoneNumber => $res) {
                if ($res['DeliveryStatus'] == 'SUCCESSFULL') {
                    event(new DeliverySuccessfull($phoneNumber, $res['DeliveryStatus'], $res['StatusMessage']));
                } else {
                    event(new DeliveryFailed($phoneNumber, $res['DeliveryStatus'], $res['StatusMessage']));
                }
            }
        } catch (Exception $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        }
    }
}
