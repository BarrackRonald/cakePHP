<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Auth\DefaultPasswordHasher;

/**
 * CRUD component
 */
class CRUDComponent extends CommonComponent
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
        $this->loadModel('Products');
        $this->loadModel('Images');
        $this->loadModel('Categories');
        $this->loadModel('Orders');
        $this->loadModel('Orderdetails');
    }

    public function getUserByID($id){
        $query = $this->Users->find()
            ->where([
                'Users.id' => $id,
            ]);
        return $query->toArray();
    }

    public function getAllRoles($key = null){
        $query = $this->Roles->find()
        ->select([
            'Roles.id',
            'Roles.role_name',
        ]);
        return $query->toArray();
    }

    public function adduser($atribute){
        $user = [];
        $user['username'] = $atribute['username'];
        $user['address'] = $atribute['address'];
        $user['email'] = $atribute['email'];
        $user['phonenumber'] = $atribute['phonenumber'];
        $hashPswdObj = new DefaultPasswordHasher;
        $user['password'] = $hashPswdObj->hash($atribute['password']);
        if($atribute['password'] == ''){
            $user['password'] = '';
        }
        $user['point_user'] = 0;
        $user['role_id'] = 1;
        $user['avatar'] = 'none.jbg';
        $user['created_date'] = date('Y-m-d h:m:s');
        $user['updated_date'] = date('Y-m-d h:m:s');
        $dataUser = $this->Users->newEntity($user);

        if ($dataUser->hasErrors()) {
            return [
                'result' => 'invalid',
                'data' => $dataUser->getErrors(),
            ];
        };
        return [
            'result' => 'success',
            'data' => $this->Users->save($dataUser),
        ];
    }

    public function getAllUser($key = null){
        $query = $this->Users->find()
        ->select([
            'Users.id',
            'Users.username',
            'Users.email',
            'Users.phonenumber',
            'Users.address',
            'Users.point_user',
            'Users.role_id',
            'Users.address',
            'Roles.role_name'
        ])
        ->join([
            'table' => 'roles',
            'alias' => 'roles' ,
            'type' => 'left',
            'conditions' => ['Users.role_id = Roles.id']
        ])
        ->order('Users.created_date DESC')
        ;
        return $query;
    }

    // Categories
    public function getAllCategory($key = null){
        $query = $this->Categories->find()
        ->select([
            'Categories.id',
            'Categories.category_name',
            'Categories.del_flag',
        ])
        ->order('Categories.created_date DESC')
        ;
        return $query;
    }
}
