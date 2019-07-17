<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Global Middleware
    |--------------------------------------------------------------------------
    | The application's global HTTP middleware stack.
    | These middleware are run during every request to your application.
    |
    */
    "global" => [
        Kaliba\Http\Middleware\POSTMiddleware::class,
        Kaliba\Http\Middleware\XSSMiddleware::class,
        App\Middleware\VerifyToken::class,
        App\Middleware\Authenticate::class,
        App\Middleware\Authorize::class
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Middleware
    |--------------------------------------------------------------------------
    | The application's route middleware.
    | These middleware may be assigned to individual routes.
    |
    */
    "route" => [

    ]

   
];
