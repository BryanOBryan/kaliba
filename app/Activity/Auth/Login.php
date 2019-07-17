<?php

namespace App\Activity\Auth;
use Kaliba\Http\PostAction;
use Kaliba\Http\Request;
use Kaliba\Robas\Auth;
use Kaliba\Support\Validation;
use Kaliba\View\WebPage;

class Login extends WebPage implements PostAction
{
    /**
     * Redirect URI
     * @var string
     */
    protected $redirect = 'dashboard';

    /**
     * View html or plain text on the web browser
     * @return mixed
     */
    public function view()
    {
       $this->render('auth.login');
    }

    /**
     * Perform action on POST REQUEST
     * @param Request $request
     * @return mixed
     */
    public function post( Request $request )
    {
        try{
            if($this->validate($request->data())){
                $username = $request->get('username');
                $password = $request->get('password');
                $remember = $request->get('remember')? true:false;
                Auth::login($username, $password, $remember);
                return redirect($this->redirect);
            }
        }catch (\Exception $exception){
            $this->input($request->data())->errors($exception->getMessage());
        }
    }

    /**
     * Validate Incoming data
     *
     * @param array
     * @return boolean
     */
    protected function validate( array $data)
    {
        $validation = new Validation($data);
        $validation->rule('required', ['username', 'password'])->message('{field} is required');
        $validation->rule('email', 'username')->message('{field} must be email');
        if(!$validation->validate()){
            $this->input($data)->errors($validation->errors());
            return false;
        }else{
            return true;
        }
    }

}