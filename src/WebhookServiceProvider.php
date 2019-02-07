<?php

namespace mrcrmn\Webhook;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use mrcrmn\Webhook\Adapter\GithubAdapter;
use mrcrmn\Webhook\Adapter\GitlabAdapter;
use mrcrmn\Webhook\Commands\DeployCommand;

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
                    return new GitlabAdapter(request());
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
    
        $this->commands([
            DeployCommand::class,
        ]);
    }
}