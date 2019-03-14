<?php
namespace App\Controllers;
use Kaliba\MVC\Controller;
use Kaliba\MVC\ViewModel;
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
	    
    public function __construct(ViewModel $viewModel)
    {
        parent::__construct($viewModel);
        $this->auth = new AuthManager();
    }
      
    public function login(Request $request)
    {   
        $validator = new Validator($request->getData());
        $validator->rule('required', 'email')->message('Email is required');
        $validator->rule('required', 'password')->message('Password is required');
		
        if( !$validator->validate() ){
            $this->viewModel->setInput($validator->data());
            $this->viewModel->setErrors($validator->errors());
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
                $this->viewModel->setErrors($this->auth->getErrors());
                $this->viewModel->setInput($request->getData());
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
