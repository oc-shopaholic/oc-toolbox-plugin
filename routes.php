<?php

Route::post(config('lovata.toolbox::route_name'), Lovata\Toolbox\Http\Controllers\ApiController::class)->middleware('api');
