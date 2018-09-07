<?php

namespace mrcrmn\Webhook\Http\Middleware;

use Closure;
use mrcrmn\Webhook\Facade\Webhook;

class VerifySignature
{
    public function handle($request, Closure $next)
    {
        if (! Webhook::verifySignature() && ! app()->isLocal()) {
            abort(401);
        }

        return $next($request);
    }
}