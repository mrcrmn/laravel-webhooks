<?php

namespace mrcrmn\Webhook\Http\Middleware;

use Closure;
use mrcrmn\Webhook\Facade\Webhook;

class IsCorrectRepository
{
    public function handle($request, Closure $next)
    {
        if (! Webhook::verifyRepository()) {
            return response("Wrong repository or wrong branch. Ignoring Request.", 401);
        }

        return $next($request);
    }
}