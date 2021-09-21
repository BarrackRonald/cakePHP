<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;
use Cake\Auth\DefaultPasswordHasher;
use PhpParser\Node\Expr\Cast\Object_;


/**
 * Data component
 */
class DataComponent extends CommonComponent
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];
    public function initialize(array $config): void
    {
        $this->loadModel(['Users']);
        $this->loadModel('Roles');
    }
    public function getAllRole(){
        $rolesTable = TableRegistry::getTableLocator()->get('Roles');
    }

    public function createUsers($atribute){
        $user = [];
        $user['username'] = $atribute['username'];
        $user['password'] = $atribute['password'];
        $user['role_id'] = 1;
        $user['avatar'] =  'default.png';
        $user['email'] =  'hoan@gmail.com';
        $user['phonenumber'] =  0000000000;
        $user['point_user'] =  111;

        $ac = $this->Users->newEntity($user);
        $result = $this->Users->save($ac);
        dd($ac->getErrors());
        if ($ac->hasErrors()) {
            return [
                'result' => 'invalid',
                'data' => $ac->getErrors(),
            ];
        }
        return [
            'result' => 'success',
            'data' =>  $result
        ];
        // dd($usersTable->save($user));



    }
}
