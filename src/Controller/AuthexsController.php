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
        $this->loadComponent('Mail');
        $this->loadModel("Users");
    }
   public function index(){
      return $this->redirect(['controller'=>'/', 'action' => 'index']);
   }

   public function login(){
        if($this->request->is('post')) {
          $email = $this->request->getData('email');
          $password = $this->request->getData('password');

          // Check rỗng và check đổi name F12
          if($email == null || $password == null ){
            $this->Flash->error('Cannot be left empty.');
            return $this->redirect(['action' => '']);
          }

          $atribute = $this->request->getData();
          $hashPswdObj = new DefaultPasswordHasher;
          $passwordDB = $this->{'Data'}->getPws($email);

          //Check email tồn tại
          $dataUserArr = $this->{'CRUD'}->getUsersByEmailArr($email);
          if(count($dataUserArr) < 1){
            $this->Flash->error('Email does not exist.');
            return $this->redirect(['action' => '']);
        }else{
          $checkPassword =  $hashPswdObj->check($password, $passwordDB[0]['password'] );

          //Check tài khoản bị khóa
            $delFlag = $this->{'CRUD'}->checkDelFlagByEmail($email);
            if(count($delFlag) > 0){
                $this->Flash->error('Your account has been locked.');
                return $this->redirect(['action' => '']);
            }

          // checkpass bằng mã hash
            if($checkPassword){
              $result = $this->{'Data'}->checklogin($atribute);
              if(count($result) > 0){
                  $idUser = $result[0]['id'];
                  $username = $result[0]['username'];
                  $session = $this->request->getSession();
                  $session->write('idUser', $idUser);
                  $session->write('username', $username);

                  //Check quyền gắn cờ
                  if($result[0]['role_id'] == 1){
                        $flag = 1;
                  }elseif ($result[0]['role_id'] == 2) {
                        $flag = 2;
                    }else{
                        $flag = 3;
                    }
                    $session->write('flag', $flag);
                  return $this->redirect(['action' => 'index']);
               } else {

                    $this->Flash->error('Your username or password is incorrect.');
               }
            }else{
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
        // dd($atribute);
        $session = $this->request->getSession();
        $dataUser = $this->{'CRUD'}->register($atribute);
        $checkmail = $this->{'Data'}->checkmail($atribute);

        $session->write('infoUser', $atribute);
        $session->write('email', $atribute['email']);

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
                    $this->redirect(['action' => 'login']);
                    $this->Flash->success(__('Đăng ký tài khoản thành công.'));
                    if($session->check('infoUser')){
                        $session->delete('infoUser');
                    }
                }else {
                    $this->Users->save($dataUser['data']);
                    $this->redirect(['action' => 'login']);
                    $this->Flash->success(__('Đăng ký tài khoản thành công.'));
                    if($session->check('infoUser')){
                        $session->delete('infoUser');
                    }
                }
            }
        }

    }

  }

  //Quên mật khẩu
  public function forgotpassword(){
    if($this->request->is('post')){
        $session = $this->request->getSession();
        $email = $this->request->getData('email');

        $dataUser = $this->{'CRUD'}->getUsersByEmail($email);
        $dataUserArr = $this->{'CRUD'}->getUsersByEmailArr($email);

        //Check rỗng
        if($email == ""){
            $error = [];
            $error['email_null'] = ['Chưa nhập Email, vui lòng kiểm tra lại!'];
            $session->write('error_forgot', $error);
            return $this->redirect(['action' => '']);
        }else {
            if($session->check('error_forgot')){
                $session->delete('error_forgot');
            }
        }

        // Checkemail tồn tài chưa
        if(count($dataUserArr) < 1){
            $error = [];
            $error['email'] = ['Địa chỉ mail chưa tồn tại. Vui lòng kiểm tra lại'];
            $session->write('error_forgot', $error);
            return $this->redirect(['action' => '']);
        }else{
            // generate random password
            $string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-';
            $randompws = substr(str_shuffle($string),0,8);

            $hasher = new DefaultPasswordHasher();
            $mypass = $hasher->hash($randompws);
            $dataUser->password =  $mypass;

            if($session->check('error_forgot')){
                $session->delete('error_forgot');
                if($this->Users->save($dataUser)){
                    $this->Flash->success('Mật khẩu của bạn đã được gửi về email ('.$email.'), vui lòng kiểm tra');
                            $to = $email;
                            $toAdmin= 'tienphamvan2005@gmail.com';
                            $subject = 'Reset Password';
                             $message = 'Mật khẩu của bạn là:'.$randompws.'';
                    $errSendMail = $this->{'Mail'}->send_mail($to, $toAdmin, $subject, $message);
                    if($errSendMail == false){
                        $this->redirect(['action' => 'login']);
                    }
                }else {
                    $this->Flash->error(__('Vui lòng thử lại.'));
                }
            }else {
                if($this->Users->save($dataUser)){
                    $this->Flash->success('Mật khẩu của bạn đã được gửi về email ('.$email.'), vui lòng kiểm tra');
                            $to = $email;
                            $toAdmin= 'tienphamvan2005@gmail.com';
                            $subject = 'Reset Password';
                             $message = 'Mật khẩu của bạn là:'.$randompws.'';
                    $errSendMail = $this->{'Mail'}->send_mail($to, $toAdmin, $subject, $message);
                    if($errSendMail == false){
                        $this->redirect(['action' => 'login']);
                    }
                }else {
                    $this->Flash->error(__('Vui lòng thử lại.'));
                }
            }
        }
    }

  }

}
?>