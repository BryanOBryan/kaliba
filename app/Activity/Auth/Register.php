<?php

namespace App\Activity\Auth;
use Kaliba\Http\PostAction;
use Kaliba\Http\Request;
use Kaliba\View\WebPage;

class Register extends WebPage implements PostAction
{
    /**
     * View html or plain text on the web browser
     * @return mixed
     */
    public function view()
    {
        $this->render('auth.register');
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