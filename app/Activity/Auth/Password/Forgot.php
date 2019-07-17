<?php

namespace App\Activity\Auth\Password;
use Kaliba\Http\GetAction;
use Kaliba\Http\PostAction;
use Kaliba\Http\Request;
use Kaliba\Robas\Auth;

class Forgot implements GetAction, PostAction
{
    /**
     * Perform action on GET REQUEST
     * @param Request $request
     * @return mixed
     */
    public function get( Request $request )
    {
        return view('auth.password.forgot');
    }

    /**
     * Perform action on POST REQUEST
     * @param Request $request
     * @return mixed
     */
    public function post( Request $request )
    {
        $email = $request->get('email');
        $user = Auth::find($email);
        if(empty($user)){
            return redirect('auth.password.forgot')->error('User not found');
        }else{
            $resetCode = $user->getResetCode();
            $link = route('auth/password/reset', $resetCode);
            print "<a href={$link}>reset password</a>";
        }
    }

}