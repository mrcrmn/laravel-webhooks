<?php

return [
    
    // Enter the name of your Github repository here.
    // This is needed, so we can verify the request.
    'repository' => 'username/repository',
    
    // Here you can set the route, where your Github webhook should point to.
    'uri' => '/webhook',

    // This is the controller which handles the event.
    'controller' => \mrcrmn\Github\Http\Controller\WebhookController::class,

    // The name of the middleware group that is applied to the route.
    'middleware-name' => 'github',

    // These are the middleware that protect your applications webhook route.
    // By default we need to verify Github's signature, verify the hash
    // and also check if we can handle the incoming Github event.
    'middleware' => [
        \mrcrmn\Github\Http\Middleware\VerifyGithubSecret::class,
        \mrcrmn\Github\Http\Middleware\CanHandleGithubEvent::class,
        // 
    ],

    // The are the Github events your application should respond to.
    // This package only handles the push event, but if you want,
    // you can extend the controller and write your own handlers.
    'events' => [
        'push',
        // '*'
    ]
    
    // This is the secret. This is needed, so your application
    // is properly protected and only Github can post to it
    // Make sure to set it when you add a Github webhook
    'secret' => env('GITHUB_SECRET', null),
    
    // If this is true, we want to put Laravel into
    // Maintenance mode while we deploy the update
    'maintenance-mode' => true,
    
    // By default we log everything that is outputted
    // on the console during deployment.
    'log_enabled' => true,
    'log_file' => 'deployments.log',
    
    // These are the commands, that should be executed as
    // part of the deployment. These are executed from
    // top to bottom. You may add your own commands
    'commands' => [
        'git',
        'composer',
        'migrate',
        'npm',
        //
    ],

    // The git pull command. If your remote or branch
    // is named differently, you may change these
    // values.
    'git' => [
        'enabled' => true,
        'command' => 'git pull',
        'remote' => 'origin',
        'branch' => 'master',
    ],

    // This command will install the necessary composer dependencies. 
    // You may specify extra flags if needed. More info available: 
    // @see https://getcomposer.org/doc/03-cli.md#install-i
    'composer' => [
        'enabled' => true,
        'command' => 'composer install',
        'flags' => ['--no-dev', '--optimize-autoloader']
    ],

    // We also want to migrate the latest changes to our database.
    'migrate' => [
        'enabled' => true,
        'command' => 'php artisan migrate',
        'flags' => ['--force']
    ],

    // Some people like to build their assets on their prod Server.
    // If you are one of those poeple, you may enable it here.
    'npm' => [
        'enabled' => false,
        'command' => 'npm install && npm run prod'
    ],

];
