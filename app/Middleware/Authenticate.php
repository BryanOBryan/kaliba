<?php

namespace App\Middleware;

use Kaliba\Robas\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
  	
    /**
     * The URLs that should be excluded .
     *
     * @var array
     */
    protected $ignore = [
        '/','index',
        'auth/login',
        'auth/register',
        'auth/password/forgot',
        'auth/password/reset'
    ];


}