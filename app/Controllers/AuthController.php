<?php
namespace App\Controllers;
use Kaliba\Mvc\Controller;
use Kaliba\Mvc\ViewModel;
use Kaliba\Http\Request;
use Kaliba\Validation\Validator;
use App\Models\AuthManager;


class AuthController extends Controller
{
    /**
     *
     * @var AuthManager
     */
    private $auth;
	    
    public function __construct(ViewModel $model)
    {
        parent::__construct($model);
        $this->auth = new AuthManager();
    }
      
    public function login(Request $request)
    {   
        $validator = new Validator($request->getData());
        $validator->rule('required', 'email')->message('Email is required');
        $validator->rule('required', 'password')->message('Password is required');
		
        if( !$validator->validate() ){
            $this->model->setInput($validator->data());
            $this->model->setErrors($validator->errors());
            redirect('index');
        }
        try{
            $email = $request->getInput('email');
            $password = $request->getInput('password');
            $remember = $request->getInput('remember')?true:false;
            $this->auth->login($email, $password, $remember);
            if($this->auth->loggedIn()){
                redirect('home');
            }else{
                $this->model->setErrors($this->auth->getErrors());
                $this->model->setInput($request->getData());
                redirect('index');
            }
        } catch (\Exception $ex) {
            $this->handle($ex);
            redirect('index');
        }
        
    }
      
    public function logout()
    {
        $this->auth->logout();
        redirect('index');
    }
    
    
}
