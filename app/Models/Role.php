<?php
namespace App\Models;
use Kaliba\ORM\Entity;

class Role extends Entity
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
    protected $title;
  
    /**
     *
     * @var int
     */
    protected $parent_id;

    /**
     *
     * @var array
     */
    protected $permissions;
    
    public function set_id(int $id)
    {
        $this->id = $id;
    }

    public function get_id(): int
    {
        return $this->id;
    }

    public function set_title(string $title)
    {
        $this->title = $title;
    }
    
    public function get_title(): string
    {
        return $this->title;
    }
    
    public function set_parent_id(int $parent_id)
    {
        $this->parent_id = $parent_id;
    }
    
    public function get_parent_id(): int
    {
        return $this->parent_id;
    }
    
    public function set_permissions($permissions)
    {
        foreach ($permissions as $permission) {
            $this->permissions[] = $permission;
        }
        
    }

    public function get_permissions()
    {
        return $this->permissions;
    }
	
    public function has_permission(string $name): bool
    {
        foreach($this->permissions as $permission){
            $_name = strtolower($permission->get_name());             
            $output = strcasecmp($name, $_name);
            return ($output == 0)?true:false;
        }
    }
    
    public function has_permissions(): bool
    {
       return !empty($this->permissions)? true:false;
    }
    
   
}
