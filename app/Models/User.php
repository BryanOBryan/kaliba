<?php
namespace App\Models;
use Kaliba\ORM\Entity;

class User extends Entity
{
    /**
     *
     * @var int
     */
    protected $id = 0;
    
    /**
     *
     * @var string
     */
    protected $firstname;
    
    /**
     *
     * @var string
     */
    protected $surname;
	
    /**
     *
     * @var string
     */
    protected $name;
    
    /**
     *
     * @var string
     */
    protected $email;
    
    /**
     *
     * @var string
     */
    protected $password= "";
    
    /**
     *
     * @var int
     */
    protected $attempts = 0;
    
    /**
     *
     * @var int
     */
    protected $role_id;
    
    
    /**
     *
     * @var bool
     */
    public $is_active;
    
     
    public function set_id(int $id)
    {
        $this->id = $id;
    }
    
    public function get_id():int
    {
        return $this->id;
    }
    
    public function set_firstname(string $firstname)
    {
        $this->firstname = $firstname;
    }
    
    public function get_firstname(): string
    {
        return $this->firstname;
    }
    
    public function set_surname(string $surname)
    {
        $this->surname = $surname;
    }
    
    public function get_surname(): string
    {
        return $this->surname;
    }
    
    public function set_name(string $name)
    {
        $this->name = $name;
    }
    
    public function get_name(): string
    {
        if(isset($this->name)){
            return $this->name;
        }else{
            return $this->firstname.' '.$this->surname;
        }
    }
    
    public function set_email(string $email)
    {
        $this->email = $email;
    }
    
    public function get_email(): string
    {
        return $this->email;
    }
    
    public function set_password(string $password)
    {
        $this->password = $password;
    }
    
    public function get_password(): string
    {
        return $this->password;
    }
    
    public function set_attempts(int $attempts)
    {
        $this->attempts += $attempts;
    }
    
    public function get_attempts(): int
    {
        return $this->attempts;
    }

    public function set_role_id(int $role_id)
    {
        $this->role_id = $role_id;
    }
    
    public function get_role_id():int
    {
        return $this->role_id;
    }
       
    public function activate()
    {
        $this->is_active = true;
    }
    
    public function deactivate()
    {
        $this->is_active = "false";
    }
    
}
