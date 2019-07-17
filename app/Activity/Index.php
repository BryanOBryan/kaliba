<?php

namespace App\Activity;
use Kaliba\View\Viewable;

class Index implements Viewable
{
    /**
     * Display html page or plain text
     * @return mixed
     */
    public function view()
    {
        return view('index');
    }
}