<?php

namespace App\Middleware;
use Kaliba\Http\Middleware\CSRFMiddleware;

class VerifyToken extends CSRFMiddleware
{
    /**
     * The URIs that should be excluded.
     *
     * @var array
     */
    protected $ignore = [
        //
    ];
}