<?php

return [
   
    'global' => [
        Kaliba\Http\Middleware\POST::class,
        Kaliba\Http\Middleware\CSRF::class,
        Kaliba\Http\Middleware\XSS::class,
        //App\Middleware\AuthMiddleware::class
    ],
    
    'route' => [
        
    ]

   
];
