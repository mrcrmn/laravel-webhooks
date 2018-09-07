<?php

namespace mrcrmn\Webhook\Facade;

use Illuminate\Support\Facades\Facade;

class Webhook extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'webhook';
    }
}