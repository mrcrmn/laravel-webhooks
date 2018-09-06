<?php

Route::post(config('github.uri'), config('github.controller'))->middleware(config('github.middleware-name'));
