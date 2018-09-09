# Laravel Webhooks
This package allows you to add closure based handlers to GitHub or GitLab Webhooks.
A 'push' handler for autodeploying is included out-of-the-box but feel free to add your own one.

## Features
* Highly configurable
* Middleware protection
* GitHub and GitLab support
* Custom event handlers

## Installation
Run `composer require mrcrmn/laravel-webhooks`.  
Publish the configuration file `php artisan vendor:publish --provider=mrcrmn/laravel-webhooks`.  
Edit the `config/webhook.php`file as needed.

#### For Laravel < 5.5
If you are not using Laravels auto discovery, you need to add `mrcrmn\Webhook\Provider\WebhookServiceProvider::class` to the `config/app.php` provider array, as well as `'Webhook' => mrcrmn\Webhook\Facade\Webhook::class` to the facades.


## Configuration

#### `repository`
Specify your git repository name here. This is needed so we can verfify the integrity of the webhook request.

#### `branch`
The branch name your events should respond to.

#### `adapter`
Currently `github` and `gitlab` are supported.

#### `uri`
This is the uri that is registered in your application. Make sure you point your git providers webhook to this uri.

#### `controller`
This is the controller that is invoked, once a request hits the uri.

#### `middleware-name`
This is the name of the middleware group that is registered. You probably don't need to change this.

#### `middleware`
This are the middleware that is run to verify the request. You can add your own to the array.

#### `events`
These are the events that your application can listen to.
By default only the push event is listened to. You can allow all events by using the `*` wildcard.

#### `secret`
This is important. Make sure you provide a secret when you are creating a webhook and put it in your `.env` file under `WEBHOOK_SECRET` or else anybody can post to your application and trigger event handlers.

#### `maintenance-mode`
If you are using the auto deploy feature, setting this to true runs `php artisan down` before the deployment commands, and `php artisan up` once all commands are executed.
*Warning*
If one of your commands returns a status code >= 1, command execution will stop and your application will stay in maintenance mode.

#### `commands`
This is the array of commands that are executed when you push to your given branch. Feel free to remove or add your own commands. Make sure you know what you are doing!

## Logs
The output of all executed commands are written to the default Log driver.
