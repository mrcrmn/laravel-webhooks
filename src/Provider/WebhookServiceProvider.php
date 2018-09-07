<?php

namespace mrcrmn\Webhook\Provider;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use mrcrmn\Webhook\Adapter\GithubAdapter;

class WebhookServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->publishes([
            __DIR__ . '/../config/webhook.php' => config_path('webhook.php')
        ]);

        $this->app->bind('webhook', function() {
            switch (config('webhook.adapter')) {
                case 'github':
                    return new GithubAdapter(request());
                    break;
                case 'gitlab':
                    // return new
                    break;
            }
        });
    }

    public function boot(Router $router)
    {
        $router->middlewareGroup(
            config('webhook.middleware-name'),
            config('webhook.middleware')
        );

        $this->loadRoutesFrom(__DIR__ . '/../routes/webhook.php');
    }
}