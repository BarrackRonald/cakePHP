<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Event\EventInterface;

/**
 * Users Controller
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    //component
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Data');
        $this->loadComponent('CRUD');
        $this->loadModel("Users");
    }

    public function beforeFilter(EventInterface $event)
    {
        $session = $this->request->getSession();
        $flag = $session->read('flag');
        if(!$session->check('flag') || $flag == 1){
            $this->Flash->error(__('Bạn không có quyền truy cập vào trang Admin.'));
            return $this->redirect(['controller'=>'NormalUsers', 'action' => 'index']);
        }
    }

    //List User
    public function listUsers()
    {
        $users = $this->{'CRUD'}->getUser();
        $this->set(compact('users', $this->paginate($users, ['limit'=> '3'])));

    }

    //Add Users
    public function addUser()
    {
        //Main
        $dataRole =  $this->{'CRUD'}->getAllRoles();
        if ($this->request->is('post')) {
            $session = $this->request->getSession();
            $atribute = $this->request->getData();
            $dataUser = $this->{'CRUD'}->adduser($atribute);
            if($dataUser['result'] == "invalid"){
                $error = $dataUser['data'];
                $session->write('error', $error);
                $this->Flash->error(__('Thêm User thất bại. Vui lòng thử lại.'));
            }else{
                if($session->check('error')){
                    $session->delete('error');
                }
                $this->Flash->success(__('User đã được thêm thành công.'));
                return $this->redirect(['action' => 'listUsers']);
            }
        }
        $this->set(compact('dataRole'));
    }

    //Edit Users
    public function editUser($id = null)
    {
        $dataUser = $this->{'CRUD'}->getUserByID($id);
        $dataRole =  $this->{'CRUD'}->getAllRoles();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $atribute = $this->request->getData();

            if($atribute['password'] == $dataUser[0]['password']){
                $atribute['password'] = $dataUser[0]['password'];
            }else {
                $hashPswdObj = new DefaultPasswordHasher;
                $atribute['password'] = $hashPswdObj->hash($atribute['password']);
            }
            $user = $this->Users->patchEntity($dataUser[0], $atribute);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('User đã được cập nhật thành công.'));
                return $this->redirect(['action' => 'listUsers']);
            }
            $this->Flash->error(__('User chưa được cập nhật. Vui lòng thử lại.'));
        }

        $this->set(compact('dataUser', 'dataRole'));
    }

    //Khóa Tài Khoản
    public function deleteUser($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dataUser = $this->{'CRUD'}->getUserByID($id);
        $atribute = $this->request->getData();
        $atribute['del_flag'] = 1;
        $user = $this->Users->patchEntity($dataUser[0], $atribute);
        if ($this->Users->save($user)) {
            $this->Flash->success(__('User đã được xóa thành công.'));
            return $this->redirect(['action' => 'listUsers']);
        }
        $this->Flash->error(__('User chưa được xóa. Vui lòng thử lại.'));
    }

    //Mở lại tài khoản
    public function opentUser($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dataUser = $this->{'CRUD'}->getUserByID($id);
        $atribute = $this->request->getData();
        $atribute['del_flag'] = 0;
        $user = $this->Users->patchEntity($dataUser[0], $atribute);
        if ($this->Users->save($user)) {
            $this->Flash->success(__('User đã được mở thành công.'));
            return $this->redirect(['action' => 'listUsers']);
        }
        $this->Flash->error(__('User chưa được mở. Vui lòng thử lại.'));
    }

    //View user

    public function viewUser($id = null)
    {
        $dataUser = $this->{'CRUD'}->getUserByID($id);
        $dataRole =  $this->{'CRUD'}->getAllRoles();
        $this->set(compact('dataUser', 'dataRole'));
    }
}
