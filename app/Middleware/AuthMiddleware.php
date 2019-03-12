<?php
namespace App\Middleware;
use Kaliba\Http\Request;
use Kaliba\Http\Middleware;
use App\Models\AuthManager;

class AuthMiddleware extends Middleware
{

    /**
     *
     * @var array
     */
    protected $accessible = [
        'index',
        'auth/login',
        'auth/logout'
        
    ];


    public function handle(Request $request)
    { 
        if($request->isGet() && $this->shouldGuard($request)) { 
            $this->guard($request);                         
        }
        $this->next($request);
    }
	
    private function guard(Request $request)
    {
        $auth = AuthManager::instance();
        $auth->checkLogin();
        if($auth->loggedIn() == false){
           return redirect('index'); 
        }             
    }
    
    private function shouldGuard(Request $request)
    {
        $path = $request->getPath();
        if(!in_array($path, $this->accessible)){
            return true;
        }else{
            return false;
        }
    }

    
}
