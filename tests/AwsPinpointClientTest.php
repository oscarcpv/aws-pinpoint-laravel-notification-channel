<?php

namespace NotificationChannels\AwsPinpoint\Test;

use Aws\Laravel\AwsFacade;
use Aws\Pinpoint\PinpointClient;
use Aws\Result;
use Illuminate\Support\Facades\Event;
use Mockery\MockInterface;
use NotificationChannels\AwsPinpoint\AwsPinpointClient;
use NotificationChannels\AwsPinpoint\AwsPinpointMessage;
use NotificationChannels\AwsPinpoint\Events\DeliveryFailed;
use NotificationChannels\AwsPinpoint\Events\DeliverySuccessfull;

class AwsPinpointClientTest extends TestCase
{
    /**
     * Test it can create Pinpoint client.
     */
    public function test_it_can_create_aws_pinpoint_client(): void
    {
        $config = config('services.aws_pinpoint');

        $pipointClient = AwsFacade::createClient('Pinpoint', [
            'region' => $config['region'],
            'credentials' => [
                'key' => $config['key'],
                'secret' => $config['secret'],
            ],
        ]);

        $this->assertInstanceOf(PinpointClient::class, $pipointClient);
    }

    public function test_send_message_successfully()
    {
        $this->mock(PinpointClient::class, function (MockInterface $mock) {
            $mock->shouldReceive('sendMessages')
                ->andReturn(new Result([
                    'MessageResponse' => [
                        'Result' => [
                            '+51999999999' => [
                                'DeliveryStatus' => 'SUCCESSFULL',
                                'StatusMessage' => 'Something good',
                            ],
                        ],
                    ],
                ]));
        });

        Event::fake([
            DeliverySuccessfull::class,
        ]);

        $config = config('services.aws_pinpoint');
        $pinpointClient = app(PinpointClient::class, [
            'args' => [
                'region' => $config['region'],
                'credentials' => [
                    'key' => $config['key'],
                    'secret' => $config['secret'],
                ],
            ],
        ]);

        $client = app(AwsPinpointClient::class, [
            'pinpointClient' => $pinpointClient,
        ]);

        $client->sendMessage(
            (new AwsPinpointMessage())
                ->body('My message')
                ->recipients('+51999999999')
        );

        Event::assertDispatched(DeliverySuccessfull::class);
    }

    public function test_send_message_with_failed_result()
    {
        $this->mock(PinpointClient::class, function (MockInterface $mock) {
            $mock->shouldReceive('sendMessages')
                ->andReturn(new Result([
                    'MessageResponse' => [
                        'Result' => [
                            '+51999999999' => [
                                'DeliveryStatus' => 'FAILED',
                                'StatusMessage' => 'Something wrong',
                            ],
                        ],
                    ],
                ]));
        });

        Event::fake([
            DeliveryFailed::class,
        ]);

        $config = config('services.aws_pinpoint');
        $pinpointClient = app(PinpointClient::class, [
            'args' => [
                'region' => $config['region'],
                'credentials' => [
                    'key' => $config['key'],
                    'secret' => $config['secret'],
                ],
            ],
        ]);

        $client = app(AwsPinpointClient::class, [
            'pinpointClient' => $pinpointClient,
        ]);

        $client->sendMessage(
            (new AwsPinpointMessage())
                ->body('My message')
                ->recipients('+51999999999')
        );

        Event::assertDispatched(DeliveryFailed::class);
    }
}
