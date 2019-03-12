<?php
namespace App\Mappers;
use Kaliba\ORM\EntityManager;
use Kaliba\ORM\Mapper;
use App\Models\User;
use App\Models\Role;

class UserMapper extends Mapper
{
    /**
     *
     * @var string
     */
    protected $tableName = 'users';

    /**
     *
     * @var string
     */
    protected $className = User::class;
        
    public function __construct() 
    {
        parent::__construct();
        $this->manager->addManyToOne(Role::class, 'role_id');
    }

    public function insert(User $user)
    {
        return $this->manager->insert($user);
    }
    
    public function update(User $user)
    {
        return $this->manager->update($user);
    }
    
    public function delete(User $user)
    {
        return $this->manager->delete($user);
    }
    
    public function fetchByEmail(string $email)
    {
        $results = parent::fetchByColumn('email', $email);
        $user = $results->first();
        return $user;
        
    }
       
    public function fetchLoginToken(string $token_key)
    {
        $manager = EntityManager::getRepository('user_logins');
        $token = $manager->fetchByColumn('token_key', $token_key)->first();
        return $token;
    }
    
    public function deleteLoginToken(string $token_key)
    {
        $manager = EntityManager::getRepository('user_logins');
        return $manager->deleteByColumn('token_key', $token_key);
    }
    
    public function insertLoginToken(\stdClass $token)
    {
        $manager = EntityManager::getRepository('user_logins');
        return $manager->insert($token);
    }
    
    public function fetchRecoveryToken(string $token_key)
    {
        $current_date = date("Y-m-d H:i:s");
        $token = $this->manager->newQuery()
                ->select()
                ->from('user_recovery')
                ->where('token_key')->equals($token_key)
                ->andWhere('expiry_date')->aboveOrEqual($current_date)
                ->execute()
                ->fetchObject();
        return $token;
    }
    
    public function insertRecoveryToken(\stdClass $token)
    {
        $manager = EntityManager::getRepository('user_recovery');
        return $manager->insert($token);
    }
}
