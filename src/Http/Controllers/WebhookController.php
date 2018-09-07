<?php

namespace mrcrmn\Webhook\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use mrcrmn\Webhook\Facade\Webhook;

class WebhookController extends Controller
{
    
    public function __invoke(Request $request)
    {
        $message = Webhook::handle();

        return response($message);
    }

}
