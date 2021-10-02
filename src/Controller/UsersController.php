<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
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
   
    //List User
    public function listUsers()
    {
        $users = $this->{'CRUD'}->getAllUser();
        $this->set(compact('users', $this->paginate($users, ['limit'=> '3'])));

    }

    //Add Users
    public function addUser()
    {
        $dataRole =  $this->{'CRUD'}->getAllRoles();
        if ($this->request->is('post')) {
            $atribute = $this->request->getData();
            $dataUser = $this->{'CRUD'}->adduser($atribute);
            if($dataUser['result'] == "invalid"){
                $this->Flash->error(__('Thêm User thất bại. Vui lòng thử lại.'));
            }else{
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
        // dd($dataUser);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($dataUser[0], $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('User đã được cập nhật thành công.'));
                return $this->redirect(['action' => 'listUsers']);
            }
            $this->Flash->error(__('User chưa được cập nhật. Vui lòng thử lại.'));
        }
        
        $this->set(compact('dataUser', 'dataRole'));
    }

    //Delete User

    public function deleteUser($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dataUser = $this->{'CRUD'}->getUserByID($id);

        if ($this->Users->delete($dataUser[0])) {
            $this->Flash->success(__('User đã được xóa thành công.'));
        } else {
            $this->Flash->error(__('User chưa được xóa. Vui lòng thử lại.'));
        }

        return $this->redirect(['action' => 'listUsers']);
    }

    //View user

    public function viewUser($id = null)
    {
        $dataUser = $this->{'CRUD'}->getUserByID($id);
        $dataRole =  $this->{'CRUD'}->getAllRoles();

        $this->set(compact('dataUser', 'dataRole'));
    }   
}
