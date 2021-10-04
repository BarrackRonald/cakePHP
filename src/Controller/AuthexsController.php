<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Locator\LocatorAwareTrait;
class AuthexsController extends AppController {
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Data');
        $this->loadComponent('CRUD');
        $this->loadModel("Users");
    }
   public function index(){
      return $this->redirect(['controller'=>'/', 'action' => 'index']);
   }

   public function login(){
        if($this->request->is('post')) {
          $con = mysqli_connect("localhost", "root", "", "cakephp");

          $email = $this->request->getData('email');
          $hashPswdObj = new DefaultPasswordHasher;
          $password = $this->request->getData('password');
          $this->getTableLocator()->get('users');
          $passworDB = $this->{'Data'}->getPws($email);
          $checkPassword =  $hashPswdObj->check($password, $passworDB[0]['password'] );
          // checkpass bằng mã hash
            if($checkPassword){
              $query = "SELECT* from users where email = '$email'";
              $result = mysqli_query($con, $query);
              $row_user = mysqli_fetch_assoc($result);
              if(mysqli_num_rows($result) > 0){
                  $idUser = $row_user['id'];
                  $username = $row_user['username'];
                  $session = $this->request->getSession();
                  $session->write('idUser', $idUser);
                  $session->write('username', $username);

                  

                  //Check quyền gắn cờ
                  if($row_user['role_id'] == 1){
                        $flag = 1;
                  }elseif ($row_user['role_id'] == 2) {
                        $flag = 2;
                    }else{
                        $flag = 3;
                    }
                    $session->write('flag', $flag);

                  return $this->redirect(['action' => 'index']);
               } else {
               $this->Flash->error('Your username or password is incorrect.');
               }
            }
        }

  }

    //Logout
  public function logout(){
      $session = $this->request->getSession();
      $session->destroy();
      return $this->redirect(['action' => 'index']);
  }

    //Đăng ký
  public function register(){
    if($this->request->is('post')) {
        $atribute = $this->request->getData();

        $session = $this->request->getSession();
        $dataUser = $this->{'CRUD'}->register($atribute);
        // dd($dataUser['data']);
        $checkmail = $this->{'Data'}->checkmail($atribute);

        if($dataUser['result'] == "invalid"){
            $error = $dataUser['data'];
            $session->write('error', $error);
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
                    $this->Flash->success(__('Đăng ký tài khoản thành công.'));
                }else {
                    $this->Users->save($dataUser['data']);
                $this->Flash->success(__('Đăng ký tài khoản thành công.'));
                }
            }
        }

    }

  }

  //Quên mật khẩu
  public function forgotPassword(){

  }
}
?>