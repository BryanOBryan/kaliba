<?php

namespace App\Middleware;
use Kaliba\Robas\Middleware\Authorize as Middleware;

class Authorize extends Middleware
{
    
    /**
     * The URLs that should be excluded .
     *
     * @var array
     */
    protected $ignore = [
        '/','index',
        'auth/login',
        'auth/logout',
        'auth/register',
        'auth/user/profile',
        'auth/password/forgot',
        'auth/password/reset'
    ];
    
}
