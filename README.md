# AWS Pinpoint Laravel Notification Channel

This package makes it easy to send notifications using [AWS Pinpoint](https://aws.amazon.com/pinpoint) with Laravel.

Only SMS is supported for now. However, any contribution that helps support other types of messages is welcome.

## Contents

- [Installation](#installation)
	- [Setting up the AWS Pinpoint service](#setting-up-the-AWS-Pinpoint-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation
Use composer to install the package:
```
composer require oscarcpv/aws-pinpoint-laravel-notification-channel
```

### Setting up the AWS Pinpoint service

First, you need to add your credentials to `config/services.php` file:

```php
<?php

return [
    
    //...

    'aws_pinpoint' => [
        'region' => env('AWS_PINPOINT_REGION', 'us-east-1'),
        'key' => env('AWS_PINPOINT_KEY'),
        'secret' => env('AWS_PINPOINT_SECRET'),
        'application_id' => env('AWS_PINPOINT_APPLICATION_ID'),
        'sms' => [
            'sender_id' => env('AWS_PINPOINT_SMS_SENDER_ID')
        ],
    ],
];
```

Now, you need to add the following entries in your `.env` file

```
AWS_PINPOINT_REGION=your-aws-region
AWS_PINPOINT_KEY=your-aws-pinpoint-key
AWS_PINPOINT_SECRET=your-aws-pinpoint-secret
AWS_PINPOINT_APPLICATION_ID=your-aws-pinpoint-application-id
AWS_PINPOINT_SMS_SENDER_ID=your-sms-sender-id
```

## Usage

In your Notification class, you must include the channel in the via method:
```php
use NotificationChannels\AwsPinpointChannel;

/**
 * Get the notification's delivery channels.
 *
 * @return array<int, string>
 */
public function via($notifiable)
{
    return ['broadcast', AwsPinpointChannel:class];
} 
```

Then, add the `toAwsPinpoint` method:

```php
use NotificationChannels\AwsPinpointMessage;

/**
 * Send SMS via AWS Pinpoint
 */
public function toAwsPinpoint($notifiable)
{
    return (new AwsPinpointMessage)
        ->body('Something cool')
        ->recipients($notifiable->phone)
        ->promotional();
} 
```

You can also define `routeNotificationForAwsPinpoint` method in the Model that uses Notifiable trait to define the recipient or recipients:
```php
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use Notifiable;

    /**
     * Route notifications for the AWS Pinpoint channel.
     *
     * @return array|string|int
     */
    public function routeNotificationForAWSPinpoint()
    {
        return $this->phone;
    }
}
```

Then, you can define your `toAwsPinpoint` method like this:

```php
use NotificationChannels\AwsPinpointMessage;

/**
 * Send SMS via AWS Pinpoint
 */
public function toAwsPinpoint($notifiable)
{
    return new AwsPinpointMessage("Something cool");
} 
```

You have available these events, for which you are free to implement listeners:
- `NotificationChannels\AwsPinpoint\Events\DeliverySuccessfull`: Is disptached after the message is sent successfully.
- `NotificationChannels\AwsPinpoint\Events\DeliveryFailed`: Is dispatched if the message could not sent successfully.

Both events receive the following parameters:
- `mixed $recipient`: The recipient the message was sent to. This parameter can be a string, an array or an integer. 
- `mixed $deliveryStatus`: The delivery status returned by AWS Pinpoint. This parameter is a string that represents the delivery status of the message. See [AWS Pinpoint Docs](https://docs.aws.amazon.com/pinpoint/latest/apireference/apps-application-id-messages.html#apps-application-id-messages-prop-messageresponse-result) for more details about this.
- `mixed $statusMessage`: The status message returned by AWS Pinpoint. This parameter is a string that contains a message describing the delivery status of the message. [AWS Pinpoint Docs](https://docs.aws.amazon.com/pinpoint/latest/apireference/apps-application-id-messages.html#apps-application-id-messages-prop-messageresponse-result) for more details about this.

### Available Message methods
When you instance the `NotificationChannels\AwsPinpoint\AwsPinpointMessage` you have available this methods: 
- `body(string $body)`: Set the body of the message
- `recipients(mixed $recipients)`: Set the recipients to which the message will be sent. This method accepts a recipient of type string or integer, or an array of recipients.
- `transactional()`: Set the message type to TRANSACTIONAL.
- `promotional()`: Set the message type to PROMOTIONAL.

You can see more details about this concepts in [AWS Pinpoint Docs](https://docs.aws.amazon.com/pinpoint/latest/apireference/apps-application-id-messages.html#SendMessages)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email [oscarcast.opv@gmail.com](mailto:oscarcast.opv@gmail.com) instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Oscar Poemape](https://github.com/oscarcpv)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
