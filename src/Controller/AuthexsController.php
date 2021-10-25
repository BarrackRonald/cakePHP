<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Event\EventInterface;

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

		$this->set(compact('dataProducts', 'dataSlideImages', 'dataNewsProducts', 'dataCategories'));
	}

	public function index()
	{
		return $this->redirect(['controller' => '/', 'action' => 'index']);
	}

	public function login()
	{
		$session = $this->request->getSession();
		if ($session->check('flag')) {
			return $this->redirect(['controller' => 'NormalUsers', 'action' => 'index']);
		}
		if ($this->request->is('post')) {
			$email = $this->request->getData('email');
			$password = $this->request->getData('password');

			// Check rỗng và check đổi name F12
			if ($email == null || $password == null) {
				$this->Flash->error('Vui lòng điền đầy đủ thông tin.');
				return $this->redirect(['action' => '']);
			}

			$atribute = $this->request->getData();
			$hashPswdObj = new DefaultPasswordHasher;
			$passwordDB = $this->{'Data'}->getPws($email);

			//Check email tồn tại
			$dataUserArr = $this->{'CRUD'}->getUsersByEmailArr($email);
			if (count($dataUserArr) < 1) {
				$this->Flash->error('Email không tồn tại.');
				return $this->redirect(['action' => '']);
			} else {
				$checkPassword =  $hashPswdObj->check($password, $passwordDB[0]['password']);

				//Check tài khoản bị khóa
				$delFlag = $this->{'CRUD'}->checkDelFlagByEmail($email);
				if (count($delFlag) > 0) {
					$this->Flash->error('Tài khoản của bạn đã bị khóa.');
					return $this->redirect(['action' => '']);
				}

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
						return $this->redirect(['action' => 'index']);
					} else {

						$this->Flash->error('Email hoặc mật khẩu chưa chính xác.');
					}
				} else {
					$this->Flash->error('Email hoặc mật khẩu chưa chính xác.');
				}
			}
		}
	}

	//Logout
	public function logout()
	{
		$session = $this->request->getSession();
		$session->destroy();
		return $this->redirect(['action' => 'index']);
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
				$error['retypePassword'] = ['Mật khẩu không khớp. Vui lòng kiểm tra lại!!!'];
				$session->write('error', $error);
				$this->redirect(['action' => '']);
			}

			if ($dataUser['result'] == "invalid") {
				$error = $dataUser['data'];
				$session->write('error', $error);
			} else {
				if ($session->check('error')) {
					$session->delete('error');
				}
				// Checkmail trùng
				$checkmail = $this->{'Data'}->checkmail($atribute);

				if (count($checkmail) > 0) {
					$error['email'] = ['Địa chỉ Email đã tồn tại.'];
					$session->write('error', $error);
					$this->redirect(['action' => '']);
				} else {
					if ($session->check('error')) {
						$session->delete('error');
						//Hash Pws
						$hashPswdObj = new DefaultPasswordHasher;
						$dataUser['data']['password'] = $hashPswdObj->hash($dataUser['data']['password']);

						if ($dataUser['data']['password'] == '') {
							$dataUser['data']['password'] = '';
						}
						dd($dataUser['data']['password']);
						$this->Users->save($dataUser['data']);
						$this->redirect(['action' => 'login']);
						$this->Flash->success(__('Đăng ký tài khoản thành công.'));
						if ($session->check('infoUser')) {
							$session->delete('infoUser');
						}
					} else {
						//Hash Pws
						$hashPswdObj = new DefaultPasswordHasher;
						$dataUser['data']['password'] = $hashPswdObj->hash($dataUser['data']['password']);

						if ($dataUser['data']['password'] == '') {
							$dataUser['data']['password'] = '';
						}
						$this->Users->save($dataUser['data']);
						$this->redirect(['action' => 'login']);
						$this->Flash->success(__('Đăng ký tài khoản thành công.'));
						if ($session->check('infoUser')) {
							$session->delete('infoUser');
						}
					}
				}
			}
		}
	}

	//Thay đổi mật khẩu
	public function changePassword()
	{
		$session = $this->request->getSession();
		if (!$session->check('flag')) {
			return $this->redirect(['controller' => 'NormalUsers', 'action' => 'index']);
		}
		if ($this->request->is('post')) {
			$atribute = $this->request->getData();
			if (($atribute['oldpassword'] == '') || ($atribute['password'] == '') || ($atribute['newretypepassword'] == '')) {
				$this->Flash->error(__('Vui lòng Điền đầy đủ các trường và thử lại.'));
				$this->redirect(['action' => '']);
			} else {
				// check retype Password
				if (!($atribute['password'] == $atribute['newretypepassword'])) {
					$this->Flash->error(__('Mật khẩu không khớp. Vui lòng nhập lại!!!'));
					$this->redirect(['action' => '']);
				} else {
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
								$this->Flash->success('Mật khẩu của bạn đã được thay đổi!!!');
							} else {
								$this->Flash->error(__('Vui lòng thử lại.'));
								$this->redirect(['action' => '']);
							}
						}
					} else {
						$this->Flash->error(__('Mật khẩu sai. Vui lòng thử lại.'));
						$this->redirect(['action' => '']);
					}
				}
			}
		}
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
				$error['email_null'] = ['Chưa nhập Email, vui lòng kiểm tra lại!'];
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
				$error['email'] = ['Địa chỉ mail chưa tồn tại. Vui lòng kiểm tra lại'];
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
						$this->Flash->success('Mật khẩu của bạn đã được gửi về email (' . $email . '), vui lòng kiểm tra');
						$to = $email;
						$toAdmin = 'tienphamvan2005@gmail.com';
						$subject = 'Reset Password';
						$message = 'Mật khẩu của bạn là:' . $randompws . '';
						$errSendMail = $this->{'Mail'}->send_mail($to, $toAdmin, $subject, $message);
						if ($errSendMail == false) {
							$this->redirect(['action' => 'login']);
						}
					} else {
						$this->Flash->error(__('Vui lòng thử lại.'));
					}
				} else {
					if ($this->Users->save($dataUser)) {
						$session->write('email', $email);
						$this->Flash->success('Mật khẩu của bạn đã được gửi về email (' . $email . '), vui lòng kiểm tra');
						$to = $email;
						$toAdmin = 'tienphamvan2005@gmail.com';
						$subject = 'Reset Password';
						$message = 'Mật khẩu của bạn là: ' . $randompws . '';
						$errSendMail = $this->{'Mail'}->send_mail($to, $toAdmin, $subject, $message);
						if ($errSendMail == false) {
							$this->redirect(['action' => 'login']);
						}
					} else {
						$this->Flash->error(__('Vui lòng thử lại.'));
					}
				}
			}
		}
	}
}
