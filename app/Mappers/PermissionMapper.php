<?php
namespace App\Mappers;
use Kaliba\ORM\Mapper;
use App\Models\Permission;

class PermissionMapper extends Mapper
{
    /**
     *
     * @var string
     */
    protected $tableName = 'permissions';

    /**
     *
     * @var string
     */
    protected $className = Permission::class;   
      
    public function fetchByName($name)
    {
        return $this->fetchByColumn('name', $name)->first();
    }
    
    public function insert(Permission $permission)
    {
        return $this->manager->insert($permission);
        
    }
    
    public function update(Permission $permission)
    {
        return $this->manager->update($permission);
    }
    
    

}
