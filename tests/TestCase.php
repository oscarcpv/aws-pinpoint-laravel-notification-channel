<?php

namespace NotificationChannels\AwsPinpoint\Test;

use Aws\Laravel\AwsServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Automatically enables package discoveries.
     *
     * @var bool
     */
    protected $enablesPackageDiscoveries = true;

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        $app['config']->set('services.aws_pinpoint.region', env('AWS_PINPOINT_REGION'));
        $app['config']->set('services.aws_pinpoint.key', env('AWS_PINPOINT_KEY'));
        $app['config']->set('services.aws_pinpoint.secret', env('AWS_PINPOINT_SECRET'));
        $app['config']->set('services.aws_pinpoint.application_id', env('AWS_PINPOINT_APPLICATION_ID'));
        $app['config']->set('services.aws_pinpoint.sms.sender_id', env('AWS_PINPOINT_SMS_SENDER_ID'));
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app)
    {
        return [
            AwsServiceProvider::class,
        ];
    }
}
