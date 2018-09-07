<?php

namespace mrcrmn\Webhook\Adapter;

use Illuminate\Http\Request;
use mrcrmn\Webhook\Facade\Webhook;


abstract class WebhookService
{
    protected $request;

    protected $handlers = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public abstract function verifySignature();

    public abstract function getEvent();

    public abstract function getPayload();

    public function canHandle($event)
    {
        $config = config('webhook.events');

        if ($config === '*') {
            return true;
        }

        if (is_array($config) && in_array('*', $config)) {
            return true;
        }

        return is_array($config) && in_array($event, $config);
    }

    protected function handlePushEvent()
    {
        $commander = new CommandExecuter(config('webhook.commands'));

        return response()->json($commander->execute());
    }

    protected function dispatchHandler($event)
    {
        return $this->handlers[$event]($this->request);
    }

    public function handle()
    {
        if (array_key_exists($this->getEvent(), $this->handlers)) {
            return $this->dispatchHandler($this->getEvent());
        }

        if ($this->getEvent() === 'push') {
            return $this->handlePushEvent();
        }

        abort(405, "No handler for '".$this->getEvent()."'");
    }
}