<?php

namespace App\Activity\Auth\Password;
use Kaliba\Http\GetAction;
use Kaliba\Http\PostAction;
use Kaliba\Http\Request;

class Change implements GetAction, PostAction
{
    /**
     * Perform action on GET REQUEST
     * @param Request $request
     * @return mixed
     */
    public function get( Request $request )
    {
        return view('auth.password.change');
    }

    /**
     * Perform action on POST REQUEST
     * @param Request $request
     * @return mixed
     */
    public function post( Request $request )
    {
        // TODO: Implement post() method.
    }


}