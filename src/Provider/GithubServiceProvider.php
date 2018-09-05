<?php

namespace mrcrmn\Github\Provider;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class GithubServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->publishes([
            __DIR__ . '/../config/github.php' => config_path('github.php')
        ]);
    }

    public function boot(Router $router)
    {
        $router->middlewareGroup(
            config('github.middleware-name'),
            config('github.middleware')
        );

        $this->loadRoutesFrom(__DIR__ . '/../routes/github.php');
    }
}