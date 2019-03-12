<?php
namespace App\Models;
use Kaliba\Support\Session;
use Kaliba\Support\Cookie;
use Kaliba\Security\Password;
use Kaliba\Security\Hash;
use App\Models\User;
use App\Mappers\UserMapper;

class AuthManager
{
    
    /**
     *
     * @var UserMapper;
     */
    protected $userMapper;

    /**
     *
     * @var array
     */
    protected $errors;
    
    /**
     *
     * @var string
     */
    protected $sessionName = 'UID';
    
    /**
     *
     * @var string
     */
    protected $cookieName = 'ULT';

    /**
     *
     * @var int
     */
    protected $rememberPeriod = 64000;
    
    /**
     *
     * @var bool
     */
    protected $shouldThrottle = false;
    
    /**
     *
     * @var int
     */
    protected $maximumAttempts = 5;
    
    /**
     *
     * @var Authentication
     */
    private static $instance;
    
    /**
     * @return AuthManager Authentication Instance
     */
    public static function instance()
    {
        if(self::$instance == null){
            self::$instance = new static();
        }
        return self::$instance;
    }
    
    public function __construct() 
    {
        $this->userMapper = new UserMapper();
        $this->errors = array();
    }
    
    /**
     * Get the current Authenticated User
     * @return User
     */
    public function getUser()
    {
        if(Session::has($this->sessionName)){
            $user_id = Session::get($this->sessionName);
            $user = $this->userMapper->fetchById($user_id);
            return $user;
        }
    }
    
    /**
     * Get authentication errors
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
    
    /**
     * Disable the throttling
     */
    public function disableThrottle()
    {
        $this->shouldThrottle = false;
    }

    /**
     * Enable the throttling feature
     */
    public function enableThrottle()
    {
        $this->shouldThrottle = true;
    }
    
    /**
     * Set the maximum number a user should attempt to login
     * @param int $maximum
     */
    public function maximumAttempts($maximum)
    {
        $this->maximumAttempts = $maximum;
    }
    
    /**
     * Set the maximum period to remember a user when remember me is activated
     * @param int|string $period
     */
    public function rememberPeriod($period)
    {
        if(is_string($period)){
            $period = strtotime($period);
        }
        $this->rememberPeriod = $period;
    }

    /**
     * Check username and password. set remember to true to remember users.
     * @param string $username Username of the user
     * @param string $password Password of the user
     * @param boolean $remember_me Remember me flag to enable user to be remembered
     * @return void
     */
    public function login($username, $password, $remember=false)
    {
        $user = $this->findUser($username);
        
        if(empty($user)){
            $this->errors[] = 'Incorrect Username';
            return false;
        }
        elseif(!$user->is_active){
            $this->errors[] = 'User is blocked';
            return false;
        } 
        elseif(!Password::verify($password, $user->get_password())){
            if($this->shouldThrottle){
                $this->checkAttempts($user); 
            }   
            $this->errors[] = 'Incorrect Password';
            return false;
        }
        else{
            Session::set($this->sessionName, $user->get_id());
            if($remember){
                $this->remember($user);
            }
        }
       
    }

    /**
     * Perform logout
     * @return void
     */
    public function logout()
    {
        if(Cookie::has($this->cookieName)){
            $cookie = Cookie::get($this->cookieName);
            $tokens = explode(':', $cookie);
            $key = $tokens[0];
            $this->userMapper->deleteLoginToken($key); 
            Cookie::destroy($this->cookieName);
        }
        if(Session::has($this->sessionName)){
            $user_id = Session::get($this->sessionName);
            $this->resetAttempts($user_id);
            Session::destroy(); 
        }  
    }   
    
    /**
     * Check whether user is logged in
     * @return boolean
     */
    public function loggedIn()
    { 
        if(Session::has($this->sessionName)){     
            return true;
        }else{
            return false;
        }
    } 
    
    /**
     * Check whether user is remembered 
     */
    public function checkLogin()
    {
		if(Cookie::has($this->cookieName)){
            $cookie = Cookie::get($this->cookieName);
            $tokens = explode(':', $cookie);
            $key = $tokens[0];
            $value = $tokens[1];
            $token = $this->userMapper->fetchLoginToken($key);         
            if(hash_equals($token->token_value, $value)){
                Session::set($this->sessionName, $token->user_id);
            }
            
        }
        
    }  
    
    /**
     * Find a user in the database by the specified username
     * @param string $criteria  id or email of the user to get from the database
     * @return User
     */
    public function findUser($criteria)
    {
        $column = null;
        if(is_int($criteria)){
            return $this->userMapper->fetchById((int)$criteria);
        }
        elseif(is_email($criteria)){
            return $this->userMapper->fetchByEmail((string)$criteria);
        }
    } 
    
    /**
     * Save the recovery token into the database
     * @param int $user_id User Id
     * @param string $token_key Recovery token key
     * @return bool
     */
    public function saveRecoveryToken($user_id, $token_key)
    {
        $expiry_date = date('Y-m-d H:i:s', strtotime('2 days') );
        $token = new \stdClass();
        $token->user_id = $user_id;
        $token->token_key = $token_key;
        $token->expiry_date = $expiry_date;
        $result = $this->userMapper->insertRecoveryToken($token);
        return ($result)?true:false;
        
    }
    
    public function checkRecoveryToken($token_key)
    {
        
    }
  
    /**
     * Remember user
     * @param User $user
     */
    private function remember($user )
    {        
        $key = Hash::unique();
        $value = Hash::unique();
        $token = new \stdClass();
        $token->token_key = $key;
        $token->token_value = $value;
        $token->user_id = $user->get_id();
        $this->userMapper->insertLoginToken($token);
        Cookie::set($this->cookieName, "{$key}:{$value}", $this->rememberPeriod);
    }
    
    /**
     * 
     * @param User $user
     * @return boolean
     */
    private function checkAttempts($user)
    {
        if($user->get_attempts() < $this->maximumAttempts){
            $user->set_attempts(1);
            $this->userMapper->update($user);
            if($user->get_attempts() == $this->maximumAttempts){
                $user->deactivate();
                $this->userMapper->update($user);
            }           
        } 
    }  
    
    private function resetAttempts($user_id)
    {
        $user = $this->userMapper->fetchById($user_id);
        $user->set_attempts(0);
        $this->userMapper->update($user);
    }
     
}
