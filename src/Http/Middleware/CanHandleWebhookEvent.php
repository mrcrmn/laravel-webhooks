<?php

namespace mrcrmn\Webhook\Http\Middleware;

use Closure;
use mrcrmn\Webhook\Facade\Webhook;

class CanHandleWebhookEvent
{
    public function handle($request, Closure $next)
    {
        if (! Webhook::canHandle(Webhook::getEvent())) {
            return response("No handler for '".Webhook::getEvent()."'", 202);
        }

        return $next($request);
    }
}