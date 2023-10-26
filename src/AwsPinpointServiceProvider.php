<?php

namespace NotificationChannels\AwsPinpoint;

use Aws\Laravel\AwsFacade;
use Illuminate\Support\ServiceProvider;
use NotificationChannels\AwsPinpoint\Exceptions\InvalidConfig;

class AwsPinpointServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(AwsPinpointChannel::class)
            ->needs(AwsPinpointClient::class)
            ->give(function () {
                $config = config('services.aws_pinpoint');

                if (is_null($config)) {
                    throw InvalidConfig::configNotFound();
                }

                $client = AwsFacade::createClient('Pinpoint', [
                    'region' => $config['region'],
                    'credentials' => [
                        'key' => $config['key'],
                        'secret' => $config['secret'],
                    ],
                ]);

                return new AwsPinpointClient($client);
            });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //
    }
}
