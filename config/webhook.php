<?php

return [
    
    // Here you need to enter the name of your git Repository.
    // This is needed, so we can verify that the Webhook does
    // actually target your application.
    'repository' => 'username/repository',

    // The production branch name.
    'branch' => 'master',

    // The adapter. Currently only GitHub and GitLab is supported.
    'adapter' => 'github',
    
    // Here you can set the route, where the webhook should point to.
    'uri' => '/webhook',

    // This is the controller which handles the event.
    'controller' => \mrcrmn\Webhook\Http\Controllers\WebhookController::class,

    // The name of the middleware group that is applied to the route.
    'middleware-name' => 'webhook',

    // These are the middleware that protect your applications webhook route.
    // By default we need to verify the request signature, verify the hash
    // and also check if we can handle the incoming webhook event.
    'middleware' => [
        \mrcrmn\Webhook\Http\Middleware\VerifySignature::class,
        \mrcrmn\Webhook\Http\Middleware\IsCorrectRepository::class,
        \mrcrmn\Webhook\Http\Middleware\CanHandleWebhookEvent::class,
        // 
    ],

    // The are the webhook events your application should respond to.
    // This package only handles the push event, but if you want,
    // you can extend the controller and write your own handlers.
    'events' => [
        'push',
        // '*'
    ],
    
    // This is the secret. This is needed, so your application
    // is properly protected and only the git provider can post to it
    // Make sure to set it when you add a webhook.
    'secret' => env('WEBHOOK_SECRET', null),
    
    // If this is true, we want to put Laravel into
    // Maintenance mode while we deploy the update.
    'maintenance-mode' => true,
    
    // These are the commands, that should be executed as
    // part of the deployment. These are executed from
    // top to bottom. You may add your own commands.
    'commands' => [
        'git pull origin master',
        'composer install --no-dev --optimize-autoloader',
        'php artisan migrate --force',
        // 'npm install',
        // 'npm run prod'
        //
    ],
];
