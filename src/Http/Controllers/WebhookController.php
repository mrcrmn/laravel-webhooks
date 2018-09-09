<?php

namespace mrcrmn\Webhook\Http\Controllers;

use Illuminate\Routing\Controller;
use mrcrmn\Webhook\Facade\Webhook;

class WebhookController extends Controller
{
    
    public function __invoke()
    {
        Webhook::handle();
    }

}
