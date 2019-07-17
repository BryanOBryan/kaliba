<?php

namespace App\Activity\Auth;
use Kaliba\Http\GetAction;
use Kaliba\Http\Request;
use Kaliba\Robas\Auth;

class Logout implements GetAction
{
    protected $redirect = 'auth/login';

    /**
     * Perform action on GET REQUEST
     * @param Request $request
     * @return mixed
     */
    public function get( Request $request )
    {
        Auth::logout();
        return redirect($this->redirect);
    }
}