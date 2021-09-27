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

         $username = $this->request->getData('username');
         $password = $this->request->getData('password');
         $users_table = $this->getTableLocator()->get('users');

         $query = "SELECT* from users where username = '$username' and password = '$password'";
         $result = mysqli_query($con, $query);
         $row_user = mysqli_fetch_assoc($result);

         if(mysqli_num_rows($result) > 0){
            die('ok');
            $idUser = $row_user['id'];
            $username = $row_user['username'];
            $session = $this->request->getSession();
            $session->write('idUser', $idUser);
            $session->write('username', $username);
            // return $this->redirect(['action' => 'index']);
         } else
         $this->Flash->error('Your username or password is incorrect.');
      }
   }
   public function logout(){
      return $this->redirect(['action' => 'login']);
   }
}
?>