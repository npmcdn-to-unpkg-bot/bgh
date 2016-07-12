<?php

namespace App\Providers;

use Barracuda\Copy\API;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Copy\CopyAdapter;
use League\Flysystem\Filesystem;
use Storage;

class CopyServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('copy', function ($app, $config) {
            $client = new API(
                $config['consumerKey'], $config['consumerSecret'],
                $config['accessToken'], $config['tokenSecret']
            );

            return new Filesystem(new CopyAdapter($client, '/'));
        });
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
