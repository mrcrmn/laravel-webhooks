<?php

namespace mrcrmn\Webhook\Http\Middleware;

use Closure;
use mrcrmn\Webhook\Facade\Webhook;

class VerifySignature
{
    public function handle($request, Closure $next)
    {
        if (! Webhook::verifySignature()) {
            abort(401, "Secret cannot be verified.");
        }

        return $next($request);
    }
}