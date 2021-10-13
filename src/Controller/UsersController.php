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
        //Check Referer
        $session = $this->request->getSession();
        if($session->check('hasReferer')){
            $session->delete('hasReferer');
        }

        if($session->check('referer')){
            $session->delete('referer');
        }

        if($session->check('error')){
            $session->delete('error');
        }

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

            // Check dữ liệu
            if(!(
                $dataRole[0]['id'] == h($atribute['role_id']) ||
                $dataRole[1]['id'] == h($atribute['role_id']) ||
                $dataRole[2]['id'] == h($atribute['role_id'])
            )){
            $this->Flash->error(__('Dữ liệu đã bị thay đổi. Không thể xác nhận thêm Người dùng!!!'));
            return $this->redirect(['action' => 'listUsers']);
            }

            $dataUser = $this->{'CRUD'}->adduser($atribute);
            if($dataUser['result'] == "invalid"){
                $error = $dataUser['data'];
                $session->write('error', $error);
                $this->Flash->error(__('Thêm User thất bại. Vui lòng thử lại.'));
            }else{
                if($session->check('error')){
                    $session->delete('error');
                }
                // Checkmail trùng
                $checkmail = $this->{'Data'}->checkmail($atribute);

                if(count($checkmail)> 0){
                    $error['email'] = ['This email address already exists.'];
                    $session->write('error', $error);
                    $this->redirect(['action' => '']);
                }else{
                    if($session->check('error')){
                        $session->delete('error');
                        $this->Users->save($dataUser['data']);
                        $this->Flash->success(__('User đã được thêm thành công.'));
                        return $this->redirect(['action' => 'listUsers']);
                    }else {
                        $this->Users->save($dataUser['data']);
                        $this->Flash->success(__('User đã được thêm thành công.'));
                        return $this->redirect(['action' => 'listUsers']);
                    }
                }

            }
        }
        $this->set(compact('dataRole'));
    }

    //Edit Users
    public function editUser($id = null)
    {
        //Check ID User
        $checkUserID = $this->{'CRUD'}->checkIDUser($id);
        if(count($checkUserID) < 1){
            $this->Flash->error(__('Người dùng không tồn tại.'));
                return $this->redirect(['action' => 'listUsers']);
        }
        $session = $this->request->getSession();
        $dataUser = $this->{'CRUD'}->getUserByID($id);
        $dataRole =  $this->{'CRUD'}->getAllRoles();

        //check Referer
        if(!$session->check('referer')){
            $referer = $_SERVER['HTTP_REFERER'];
            $session->write('referer', $referer);
        }

        $getReferer = $session->read('referer');

        if ($this->request->is(['patch', 'post', 'put'])) {
            $atribute = $this->request->getData();

            //Check thay đổi
            if(trim($atribute['username']) == trim($checkUserID[0]['username']) &&
            trim($atribute['password']) == trim($checkUserID[0]['password']) &&
            trim($atribute['phonenumber']) == trim($checkUserID[0]['phonenumber']) &&
            trim($atribute['address']) == trim($checkUserID[0]['address']) &&
            $atribute['role_id'] == $checkUserID[0]['role_id']
             ){
                $this->Flash->error(__('Dữ liệu không có sự thay đổi.'));
                return $this->redirect("$getReferer");
            }

            // Check dữ liệu F12
            if(!(
                $dataRole[0]['id'] == h($atribute['role_id']) ||
                $dataRole[1]['id'] == h($atribute['role_id']) ||
                $dataRole[2]['id'] == h($atribute['role_id'])
            )){
            $this->Flash->error(__('Dữ liệu đã bị thay đổi. Không thể xác nhận chỉnh sửa Người dùng!!!'));
            return $this->redirect(['action' => 'listUsers']);
            }

            if($atribute['password'] == $dataUser[0]['password']){
                $atribute['password'] = $dataUser[0]['password'];
            }else {
                $hashPswdObj = new DefaultPasswordHasher;
                $atribute['password'] = $hashPswdObj->hash($atribute['password']);
            }
            $user = $this->Users->patchEntity($dataUser[0], h($atribute));

            if ($user->hasErrors()) {
                $error = $user->getErrors();
                $session->write('error', $error);
                return $this->redirect("");
            }else {
                if($session->check('error')){
                    $session->delete('error');
                }
            }

            if ($this->Users->save($user)) {
                if($session->check('hasReferer')){
                    $session->delete('hasReferer');
                }

                if($session->check('referer')){
                    $session->delete('referer');
                }

                $this->Flash->success(__('User đã được cập nhật thành công.'));
                return $this->redirect("$getReferer");
            }
            $this->Flash->error(__('User chưa được cập nhật. Vui lòng thử lại.'));

        }
        else
        {
            
        }
        $this->set(compact('dataUser', 'dataRole'));
    }

    //Khóa Tài Khoản
    public function deleteUser($id = null)
    {
        $urlPageList = $_SERVER['HTTP_REFERER'];
        $this->request->allowMethod(['post', 'delete']);
        $dataUser = $this->{'CRUD'}->getUserByID($id);
        $atribute = $this->request->getData();
        $atribute['del_flag'] = 1;
        $user = $this->Users->patchEntity($dataUser[0], $atribute);

        if ($this->Users->save($user)) {
            $this->Flash->success(__('User đã được xóa thành công.'));
            return $this->redirect("$urlPageList");
        }
        $this->Flash->error(__('User chưa được xóa. Vui lòng thử lại.'));
        return $this->redirect("$urlPageList");
    }

    //Mở lại tài khoản
    public function opentUser($id = null)
    {
        $urlPageList = $_SERVER['HTTP_REFERER'];
        $this->request->allowMethod(['post', 'delete']);
        $dataUser = $this->{'CRUD'}->getUserByID($id);
        $atribute = $this->request->getData();
        $atribute['del_flag'] = 0;
        $user = $this->Users->patchEntity($dataUser[0], $atribute);
        if ($this->Users->save($user)) {
            $this->Flash->success(__('User đã được mở thành công.'));
            return $this->redirect("$urlPageList");
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
