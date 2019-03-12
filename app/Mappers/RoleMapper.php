<?php
namespace App\Mappers;
use Kaliba\ORM\Mapper;
use App\Models\Role;
use App\Models\Permission;
use Kaliba\ORM\EntityCollection;


class RoleMapper extends Mapper
{
    /**
     *
     * @var string
     */
    protected $tableName = 'roles';
    
    /**
     *
     * @var string
     */
    protected $className = Role::class;
    
    /**
     *
     * @var string
     */
    protected $linkTable = 'role_permissions';
    
    /**
     *
     * @var string
     */
    protected $permissionTable = 'permissions';

    public function __construct() 
    {
        parent::__construct();
        $this->manager
                ->addManyToMany($this->permissionTable, $this->linkTable, ['role_id', 'permission_id'])
                ->className(Permission::class); 
    }
    
    public function fetchById($id) 
    {
        $role = $this->manager->fetchById($id);
        if(!empty($role)){
            return $this->resolve($role);
        }
    }
    
    public function fetchByTitle($title)
    {
        $role = $this->manager->fetchByColumn('title', $title)->first();
        if(!empty($role)){
            return $this->resolve($role);
        }
    }
    
    public function fetchAll($limit = null, $offset = null) 
    {
        $roles = $this->manager->fetchAll($limit, $offset);
        $collection = new EntityCollection();
        foreach ($roles as $role) {
            $_role = $this->resolve($role);
            $collection->add($_role);
        }
        return $collection;
    }
    
    public function insert(Role $role)
    {
        return $this->manager->insert($role);
    }
    
    public function update(Role $role)
    {
        return $this->manager->update($role);
    }
    
    public function delete(Role $role)
    {
        return $this->manager->delete($role);
    }
    
    public function insertPermission(int $role_id, int $permission_id)
    {
        $result = $this->manager
                ->newQuery()
                ->insert()
                ->into($this->linkTable)
                ->values([
                    'role_id' => $role_id,
                    'permission_id' => $permission_id
                ])
                ->execute();
        return ($result->rowCount() >= 1)?true:false;  

    }
    
    public function deletePermission(int $role_id, int $permission_id)
    {
        $result = $this->manager
                ->newQuery()
                ->delete()
                ->from($this->permissionTable)
                ->where('role_id')->equals($role_id)
                ->andWhere('permission_id')->equals($permission_id) 
                ->execute();
        return ($result->rowCount() >=1 )?true:false;
    }
        
    public function fetchPermissions($role_id)
    {
        $role = $this->fetchById($role_id);
        return $role->get_permissions();
    }
    
    private function resolve(Role $role)
    {       
        $parent = $this->fetchById($role->get_parent_id());
        if(!empty($parent)){
            $role->set_permissions($parent->get_permissions());
        }
        return $role;
    }
      
    
    
   
}
