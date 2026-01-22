<?php
return [

    /*
    |--------------------------------------------------------------------------
    | GraphQL Endpoint
    |--------------------------------------------------------------------------
    |
    | Set the endpoint to which the GraphQL server responds.
    | The default route endpoint is "yourdomain.com/graphql".
    |
     */

    'api_route_name' => env('TOOLBOX_API_ROUTE', 'graphql'),

    /*
    |--------------------------------------------------------------------------
    |  API route middlewares
    |--------------------------------------------------------------------------
    |
    | List of middleware assigned to API route.
    |
     */

    'api_route_middleware' => [
        'api',
    ],

    /*
    |--------------------------------------------------------------------------
    | Schema Declaration
    |--------------------------------------------------------------------------
    |
    | This is a path that points to where your GraphQL schema is located
    | relative to the app path. You should define your entire GraphQL
    | schema in this file (additional files may be imported).
    |
     */

    //'schema' => [
    //    'register' => plugins_path('lovata/toolbox/classes/api/schema.graphql'),
    //],


];
