<?php

namespace App\Activity\Auth\Password;
use Kaliba\Http\GetAction;
use Kaliba\Http\PostAction;
use Kaliba\Http\Request;
use Kaliba\Robas\Auth;
use Kaliba\Security\Password;

class Reset implements GetAction, PostAction
{
    /**
     * Perform action on GET REQUEST
     * @param Request $request
     * @return mixed
     */
    public function get( Request $request )
    {
        $code = $request->get('id');
        try{
            $user = Auth::checkResetCode($code);
            return view('auth.password.reset', [
                'email' => $user->email
            ]);
        }catch (\Exception $ex){
            return redirect('auth.password.forgot')->error($ex->getMessage());
        }

    }

    /**
     * Perform action on POST REQUEST
     * @param Request $request
     * @return mixed
     */
    public function post( Request $request )
    {
        $email = $request->get('email');
        $password = $request->get('password');
        $user = Auth::find($email);
        if(empty($user)){
            return redirect('auth.password.forgot')->error('User not found');
        }else{
            $user->save(['password' => Password::hash($password) ]);
            return redirect('auth.login')->success('Password reset successful');
        }
    }


}