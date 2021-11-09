<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Event\EventInterface;
use Cake\Routing\Router;

class AuthexsController extends AppController
{
	public function initialize(): void
	{
		parent::initialize();
		$this->loadComponent('Data');
		$this->loadComponent('CRUD');
		$this->loadComponent('Mail');
		$this->loadModel("Users");
	}
	public function beforeRender(EventInterface $event)
	{
		$dataCategories = $this->{'Data'}->getCategory();
		$dataProducts = $this->{'Data'}->getAllProducts();
		$dataSlideImages = $this->{'Data'}->getSlideImage();
		$dataNewsProducts = $this->{'Data'}->getNewsProduct();
		$session = $this->request->getSession();

		if ($session->check('idUser')) {
			$idUsers = $session->read('idUser');
			$dataNameForUser = $this->{'Data'}->getInfoUser($idUsers);
			$this->set(compact('dataNameForUser'));
		}

		$this->set(compact('dataProducts', 'dataSlideImages', 'dataNewsProducts', 'dataCategories'));
	}

	public function beforeFilter(EventInterface $event)
	{
		$session = $this->request->getSession();
		if ($session->check('flag')) {
			$idUser = $session->read('idUser');
			$check = $this->{'CRUD'}->checkUserLock($idUser);
			if(count($check) < 1){
				$session->destroy();
				$this->Flash->error(__(ERROR_LOCK_ACCOUNT));
				return $this->redirect(Router::url(['_name' => NAME_LOGIN]));
			}
		}
	}

	public function index()
	{
		return $this->redirect(['controller' => 'NormalUsers', 'action' => NORMALUSER_INDEX]);
	}

	public function login()
	{
		$session = $this->request->getSession();
		if ($session->check('flag')) {
			return $this->redirect(['action' => AUTH_INDEX]);
		}
		if ($this->request->is('post')) {
			$email = $this->request->getData('email');
			$password = $this->request->getData('password');

			//Lưu oldValue
			$session->write('email', $email);
			$session->write('password', $password);

			// Check rỗng và check đổi name F12
			if ($email == null || $password == null) {
				$this->Flash->error(ERROR_FULL_INFOR);
				return $this->redirect(['action' => '']);
			}

			$atribute = $this->request->getData();
			$hashPswdObj = new DefaultPasswordHasher;
			$passwordDB = $this->{'Data'}->getPws($email);

			//Check tài khoản bị khóa
			$delFlag = $this->{'CRUD'}->checkDelFlagByEmail($email);
			if (count($delFlag) > 0) {
				$this->Flash->error(ERROR_LOCK_ACCOUNT);
				return $this->redirect(['action' => '']);
			}

			//Check email tồn tại
			$dataUserArr = $this->{'CRUD'}->getUsersByEmailArr($email);
			if (count($dataUserArr) < 1) {
				$this->Flash->error(ERROR_EMAIL_EMPTY);
				return $this->redirect(['action' => '']);
			} else {
				$checkPassword =  $hashPswdObj->check($password, $passwordDB[0]['password']);

				// checkpass bằng mã hash
				if ($checkPassword) {
					$result = $this->{'Data'}->checklogin($atribute);
					if (count($result) > 0) {
						$idUser = $result[0]['id'];
						$username = $result[0]['username'];
						$session = $this->request->getSession();
						$session->write('idUser', $idUser);
						$session->write('username', $username);

						//Check quyền gắn cờ
						if ($result[0]['role_id'] == 1) {
							$flag = 1;
						} elseif ($result[0]['role_id'] == 2) {
							$flag = 2;
						} else {
							$flag = 3;
						}
						$session->write('flag', $flag);

						//Check nếu là admin hoặc employee thì đi thẳng đến admin
						if($flag == 2 || $flag == 3){
							return $this->redirect(URL_INDEX_ADMIN);
						}else{
							return $this->redirect(['action' => AUTH_INDEX]);
						}
					} else {
						$this->Flash->error(ERROR_EMAIL_PWS_INCORRECT);
					}
				} else {
					$this->Flash->error(ERROR_EMAIL_PWS_INCORRECT);
				}
			}
		}
	}

	//Logout
	public function logout()
	{
		$session = $this->request->getSession();
		$session->destroy();
		return $this->redirect(['action' => AUTH_INDEX]);
	}

	//Đăng ký
	public function register()
	{
		if ($this->request->is('post')) {
			$atribute = $this->request->getData();

			$session = $this->request->getSession();
			$dataUser = $this->{'CRUD'}->register($atribute);
			$checkmail = $this->{'Data'}->checkmail($atribute);

			$session->write('infoUser', $atribute);
			$session->write('email', $atribute['email']);
			$session->write('password', $atribute['password']);

			// check retype Password
			if (!($atribute['password'] == $atribute['retypePassword'])) {
				$error['retypePassword'] = [ERROR_PASSWORD_NOT_MATCH];
				$session->write('error', $error);
				$this->redirect(['action' => '']);
			}else{
				if ($dataUser['result'] == "invalid") {
					$error = $dataUser['data'];
					$session->write('error', $error);
				} else {
					if ($session->check('error')) {
						$session->delete('error');
					}

					//Hash Pws
					$hashPswdObj = new DefaultPasswordHasher;
					$dataUser['data']['password'] = $hashPswdObj->hash($dataUser['data']['password']);

					if ($dataUser['data']['password'] == '') {
						$dataUser['data']['password'] = '';
					}
					$this->Users->save($dataUser['data']);
					$this->redirect(['action' => AUTH_LOGIN]);
					$this->Flash->success(__(SUCCESS_ACCOUNT));
					if ($session->check('infoUser')) {
						$session->delete('infoUser');
					}
				}
			}
		}
	}

	//Thay đổi mật khẩu
	public function changePassword()
	{
		$session = $this->request->getSession();
		$data = null;
		if (!$session->check('flag')) {
			return $this->redirect(['action' => AUTH_INDEX]);
		}
		if ($this->request->is('post')) {
			$atribute = $this->request->getData();
			if (($atribute['oldpassword'] == '') || ($atribute['password'] == '') || ($atribute['newretypepassword'] == '')) {
				$this->Flash->error(__(ERROR_FULL_INFOR));
				$data = $atribute;
			} else {
				// check retype Password
				if (!($atribute['password'] == $atribute['newretypepassword'])) {
					$this->Flash->error(__(ERROR_PASSWORD_NOT_MATCH));
					$data = $atribute;
				} else {
					if(($atribute['password'] == $atribute['oldpassword'])){
						$this->Flash->error(__(ERROR_PASSWORD_NOT_CHANGED));
						$data = $atribute;
					}else{
						$idUser = $session->read('idUser');
						$dataUser = $this->{'CRUD'}->getUsersByID($idUser);
						$hashPswdObj = new DefaultPasswordHasher;
						$checkPassword =  $hashPswdObj->check($atribute['oldpassword'], $dataUser['password']);
						if ($checkPassword) {
							$user = $this->Users->patchEntity($dataUser, $atribute);
							if ($user->hasErrors()) {
								$error = $user->getErrors();
								$this->set('error', $error);
							} else {
								$newpass = $hashPswdObj->hash($atribute['password']);
								$user->password = $newpass;
								if ($this->Users->save($user)) {
									$this->Flash->success(SUCCESS_PASSWORD_CHANGED);
								} else {
									$this->Flash->error(__(ERROR_RETRY));
									$data = $atribute;
								}
							}
						} else {
							$error['errPassword'] = [ERROR_PWS_INCORRECT] ;
							$this->set('error', $error);
							$data = $atribute;
						}
					}
				}
			}
		}

		$this->set('dataPassword', $data);
	}

	//Quên mật khẩu
	public function forgotPassword()
	{
		if ($this->request->is('post')) {
			$session = $this->request->getSession();
			$email = $this->request->getData('email');

			$dataUser = $this->{'CRUD'}->getUsersByEmail($email);
			$dataUserArr = $this->{'CRUD'}->getUsersByEmailArr($email);

			//Check rỗng
			if ($email == "") {
				$error = [];
				$error['email_null'] = [ERROR_NOT_INPUT_EMAIL];
				$session->write('error_forgot', $error);
				return $this->redirect(['action' => '']);
			} else {
				if ($session->check('error_forgot')) {
					$session->delete('error_forgot');
				}
			}

			// Checkemail tồn tài chưa
			if (count($dataUserArr) < 1) {
				$error = [];
				$error['email'] = [ERROR_EMAIL_EMPTY];
				$session->write('error_forgot', $error);
				return $this->redirect(['action' => '']);
			} else {
				// generate random password
				$string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-';
				$randompws = substr(str_shuffle($string), 0, 8);

				$hasher = new DefaultPasswordHasher();
				$mypass = $hasher->hash($randompws);
				$dataUser->password =  $mypass;

				if ($session->check('error_forgot')) {
					$session->delete('error_forgot');
					if ($this->Users->save($dataUser)) {

						//Lưu và xóa Session
						$session->write('email', $email);
						if($session->check('password')){
							$session->delete('password');
						}

						$this->Flash->success('Mật khẩu của bạn đã được gửi về email (' . $email . '), vui lòng kiểm tra');
						$to = $email;
						$toAdmin = 'phamhoan020501@gmail.com';
						$subject = 'Reset Password';
						$message = 'Mật khẩu của bạn là:' . $randompws . '';
						$errSendMail = $this->{'Mail'}->send_mail($to, $toAdmin, $subject, $message);
						if ($errSendMail == false) {
							$this->redirect(['action' => AUTH_LOGIN]);
						}
					} else {
						$this->Flash->error(__(ERROR_RETRY));
					}
				} else {
					if ($this->Users->save($dataUser)) {

						//Lưu và xóa Session
						$session->write('email', $email);
						if($session->check('password')){
							$session->delete('password');
						}

						$this->Flash->success('Mật khẩu của bạn đã được gửi về email (' . $email . '), vui lòng kiểm tra');
						$to = $email;
						$toAdmin = 'tienphamvan2005@gmail.com';
						$subject = 'Reset Password';
						$message = 'Mật khẩu của bạn là: ' . $randompws . '';
						$errSendMail = $this->{'Mail'}->send_mail($to, $toAdmin, $subject, $message);
						if ($errSendMail == false) {
							$this->redirect(['action' => AUTH_LOGIN]);
						}
					} else {
						$this->Flash->error(__(ERROR_RETRY));
					}
				}
			}
		}
	}
}
