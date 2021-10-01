<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Locator\LocatorAwareTrait;
class AuthexsController extends AppController {
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
                  return $this->redirect(['action' => 'index']);
               } else {
               $this->Flash->error('Your username or password is incorrect.');
               }
          }
      }

  }

  public function logout(){
      $session = $this->request->getSession();
      $session->destroy();
      return $this->redirect(['action' => 'index']);
  }

  //Register (sau)
  public function register(){

  }
}
?>