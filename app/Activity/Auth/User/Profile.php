<?php


namespace App\Activity\Auth\User;
use Kaliba\View\Viewable;

class Profile implements Viewable
{
    /**
     * Display html page or plain text
     * @return mixed
     */
    public function view()
    {
        return view('auth.user.profile');
    }
}