<?php

namespace mrcrmn\Github\Http\Middleware;

use Closure;

class VerifyGithubSecret
{
    public function handle($request, Closure $next)
    {
        list($algo, $hash) = explode('=', $request->header('x-hub-signature'), 2);

        if ($hash !== hash_hmac($algo, $request->getContent(), config('github.secret'))) {
            abort(401);
        }

        return $next($request);
    }
}