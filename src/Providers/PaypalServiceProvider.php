<?php

namespace Andreytech\PaypalWebhook\Providers;

use Andreytech\PaypalWebhook\Commands\CreateSubscription;
use Illuminate\Support\ServiceProvider;

class PaypalServiceProvider extends ServiceProvider
{
    /**
     * commands.
     * @var array
     */
    protected $commands = [
    ];

    /**
     * register method.
     */
    public function register()
    {
//        $this->commands($this->commands);
    }

    /**
     * boot method.
     */
    public function boot()
    {
        // config
        $this->mergeConfigFrom(__DIR__.'/../../config/paypal_webhook.php', 'paypal_webhook');
        $this->publishes([__DIR__.'/../../config' => config_path()], 'config');
        // migrations
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->publishes([__DIR__.'/../../database' => database_path()], 'migrations');
        // routes
        $this->loadRoutesFrom(__DIR__.'/../../routes/routes.php');
    }
}
