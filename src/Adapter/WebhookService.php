<?php

namespace mrcrmn\Webhook\Adapter;

use Closure;
use Illuminate\Http\Request;
use mrcrmn\Webhook\Facade\Webhook;
use Illuminate\Support\Facades\Artisan;

abstract class WebhookService
{
    /**
     * The request which holds the payload and headers.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The array of handlers and callbacks.
     *
     * @var array
     */
    protected $handlers = [];

    /**
     * The constructor.
     * 
     * We set the request object and initialize the push handler.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->handler('push', function($request) {
            Artisan::call('deploy');
        });
    }

    /**
     * Verifies the origin of the client.
     * Github and Gitlab supply a token or hash in their request
     * header which we can use to verify the integrity of the request
     *
     * @return bool
     */
    public abstract function verifySignature();

    /**
     * Gets the event name. Github has it in its header but Gitlab
     * puts it in the request body. So we need an adapter for that.
     *
     * @return string
     */
    public abstract function getEvent();

    /**
     * Gets the playload from the request.
     *
     * @return array
     */
    public abstract function getPayload();

    /**
     * Verifies the repository name and branch.
     *
     * @return bool
     */
    public abstract function verifyRepository();

    /**
     * Checks if the event name is specified in the config and
     * if we have a handler registered for this event.
     *
     * @param string $event
     *
     * @return bool
     */
    public function canHandle($event)
    {
        $config = config('webhook.events');

        // Return true if a wildcard isset.
        if ($config === '*' || (is_array($config) && in_array('*', $config))) {
            return true;
        }

        // Return true if the event name is in the array.
        return is_array($config) && in_array($event, $config);
    }

    /**
     * Calls the handler function.
     *
     * @param string $event
     *
     * @return void
     */
    protected function dispatchHandler($event)
    {
        return $this->handlers[$event]($this->request);
    }

    /**
     * Registers a handler for the given event.
     *
     * @param string $event
     * @param \Closure $callback
     *
     * @return void
     */
    public function handler($event, Closure $callback)
    {
        $this->handlers[$event] = $callback;
    }

    /**
     * Dispatches the handler.
     *
     * @return void
     */
    public function handle()
    {
        if (array_key_exists($this->getEvent(), $this->handlers)) {
            return $this->dispatchHandler($this->getEvent());
        }

        abort(405, "No handler for '".$this->getEvent()."'");
    }
}