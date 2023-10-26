<?php

namespace NotificationChannels\AwsPinpoint\Test;

use NotificationChannels\AwsPinpoint\AwsPinpointMessage;

class AwsPinpointMessageTest extends TestCase
{
    /**
     * Test it can instantiate AwsPinpointMessage.
     */
    public function test_it_can_be_instantiated(): void
    {
        $message = new AwsPinpointMessage();

        $this->assertInstanceOf(AwsPinpointMessage::class, $message);
        $this->assertEquals(AwsPinpointMessage::TRANSACTIONAL_SMS_TYPE, $message->type);
        $this->assertEquals('', $message->body);
    }

    /**
     * Test it can instantiate AwsPinpointMessage with not empty body.
     */
    public function test_it_can_be_instantiated_with_not_empty_body(): void
    {
        $message = new AwsPinpointMessage('Something');

        $this->assertEquals('Something', $message->body);
    }

    /**
     * Test it can instantiate AwsPinpointMessage with create method.
     */
    public function test_it_can_be_instantiated_with_create_method(): void
    {
        $message = AwsPinpointMessage::create('Something');

        $this->assertInstanceOf(AwsPinpointMessage::class, $message);
        $this->assertEquals('Something', $message->body);
    }

    /**
     * Test it can set body.
     */
    public function test_it_can_set_body(): void
    {
        $message = new AwsPinpointMessage();
        $message->body('Something');

        $this->assertEquals('Something', $message->body);
    }

    /**
     * Test it can set recipients from array.
     */
    public function test_it_cant_set_recipients_from_array(): void
    {
        $message = new AwsPinpointMessage();
        $message->recipients(['+51123456789', 51987654321]);

        $this->assertEquals('+51123456789', array_keys($message->recipients)[0]);
        $this->assertEquals(51987654321, array_keys($message->recipients)[1]);
    }

    /**
     * Test it can set recipients from string.
     */
    public function test_it_can_be_instantiated_from_string(): void
    {
        $message = new AwsPinpointMessage();
        $message->recipients('+51987654321');

        $this->assertEquals('+51987654321', array_keys($message->recipients)[0]);
    }

    /**
     * Test it can set recipients from integer.
     */
    public function test_it_can_be_instantiated_from_integer(): void
    {
        $message = new AwsPinpointMessage();
        $message->recipients(51987654321);

        $this->assertEquals(51987654321, array_keys($message->recipients)[0]);
    }

    public function test_it_can_change_message_type(): void
    {
        $message = new AwsPinpointMessage();
        $message->promotional();

        $this->assertEquals($message->type, AwsPinpointMessage::PROMOTIONAL_SMS_TYPE);
    }
}
