<?php

Route::post(config('webhook.uri'), config('webhook.controller'))->middleware(config('webhook.middleware-name'));
