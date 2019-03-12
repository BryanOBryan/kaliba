<?php
namespace App\Models;
use Kaliba\ORM\Entity;

class Permission extends Entity
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
    protected $name;
    
    /**
     *
     * @var string
     */
    protected $target;
    
    /**
     *
     * @var string
     */
    protected $module;
    
    public function set_id(int $id)
    {
        $this->id = $id;
    }
    
    public function get_id(): int
    {
        return $this->id;
    }

    public function set_name(string $name)
    {
        $this->name = $name;
    }
    
    public function get_name(): string
    {
        return $this->name;
    }   

    public function set_target(string $target)
    {
        $this->target = $target;
    }
    
    public function get_target(): string
    {
        return $this->target;
    }
    
    public function set_module($module)
    {
        $this->module = $module;
    }
    
    public function get_module()
    {
        return $this->module;
    }
    
}
