<?php

$sApiEndpoint = config('lovata.toolbox::api_route_name');
$arApiMiddlewareList = config('lovata.toolbox::api_route_middleware');

Route::post($sApiEndpoint, Lovata\Toolbox\Http\Controllers\ApiController::class)->middleware($arApiMiddlewareList);
