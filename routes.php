<?php

use Lovata\Toolbox\Http\Controllers\ApiController;

Route::post(config('lovata.toolbox::route_name'), function () {
    $obApiController = new ApiController(Request::instance());
    $sResponse = $obApiController->query();

    return $sResponse;

})->middleware('api');
