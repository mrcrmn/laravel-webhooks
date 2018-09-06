<?php

namespace mrcrmn\Github\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WebhookController extends Controller
{
    
    protected function handleEvent($request)
    {
        $event = $request->header('X-GitHub-Event');

        return $this->${$event}($request);
    }

    public function __invoke(Request $request)
    {
        return $this->handleEvent($request);
        dump($request->all());
    }

    public function push()
    {

    }
}
