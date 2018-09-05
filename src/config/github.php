<?php

return [

    'uri' => '/webhook',

    'controller' => \mrcrmn\Github\Http\Controller\WebhookController::class,

    'middleware' => [
        \mrcrmn\Github\Http\Middleware\VerifyGithubSecret::class,
        //
    ],

    'secret' => env('GITHUB_SECRET', null),

    'commands' => [
        'git',
        'composer',
        'migrate',
        'npm',
        //
    ],

    'git' => [
        'enabled' => true,
        'command' => 'git pull',
        'remote' => 'origin',
        'branch' => 'master',
    ],

    'composer' => [
        'enabled' => true,
        'command' => 'composer install',
        'flags' => ['--no-dev', '--optimize-autoloader']
    ],

    'migrate' => [
        'enabled' => true,
        'command' => 'php artisan migrate',
        'flags' => ['--force']
    ],

    'npm' => [
        'enabled' => false,
        'command' => 'npm run prod'
    ],

];
