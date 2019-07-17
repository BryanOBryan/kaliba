<?php


return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    */

    'name' => 		'app',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Kaliba encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => 'QhMvMC2ATY8avoJElqNzdWFQwy6heDZiAV34adkC2CY=',

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Application Activity namespace
    |--------------------------------------------------------------------------
    |
    | Namespace for the mvc components.
    | During Request routing, the Router uses these namespaces to build MVC components
    |
    */
    'namespace'    =>  'App\\Activity',

    /*
    |--------------------------------------------------------------------------
    | Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */
    'providers'      =>  [
        Kaliba\Database\DatabaseProvider::class,
        Kaliba\View\ViewProvider::class
    ]


];
    
    