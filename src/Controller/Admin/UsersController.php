<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Routing\Router;

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
		if (!$session->check('flag') || $flag == 1) {
			$this->Flash->error(__(ERROR_ROLE_ADMIN));
			return $this->redirect('/');
		}else{
			$idUser = $session->read('idUser');
			$check = $this->{'CRUD'}->checkUserLock($idUser);
			if(count($check) < 1){
				$session->destroy();
				$this->Flash->error(__(ERROR_LOCK_ACCOUNT));
				return $this->redirect(Router::url(['_name' => NAME_LOGIN]));
			}
		}
	}

	public function beforeRender(EventInterface $event)
	{
		$session = $this->request->getSession();

		if ($session->check('idUser')) {
			$idUsers = $session->read('idUser');
			$dataNameForUser = $this->{'Data'}->getInfoUser($idUsers);
			$this->set(compact('dataNameForUser'));
		}
	}

	//List User
	public function listUsers()
	{
		$users = $this->{'CRUD'}->getUser();
		//Search
		$key = $this->request->getQuery('key');
		$filter = $this->request->getQuery('filter');
		$session = $this->request->getSession();
		if ($key) {
			//Lưu key
			$session->write('keySearch', trim($key));
			$query = $this->{'CRUD'}->getSearchUser(trim($key));
			$queryArr = $this->{'CRUD'}->getSearchUsertoArr(trim($key));

			if(count($queryArr) == 0){
				$this->Flash->error(__(ERROR_SEARCH_NOT_FOUND));
			}
		}else if($filter){
			$session->write('hasfilter', 1);
			//Lọc
			if($filter == 'lock'){
				$filters = 1;
			}elseif($filter == 'unlock'){
				$filters = 0;
			}else{
				$this->Flash->error(__(ERROR_DATA_FILTER_CHANGED));
				return $this->redirect(['action' => ADMIN_LIST_USERS]);
			}
			$this->set('filters', $filter);

			$query = $this->{'CRUD'}->filterUser($filters);
		} else {
			if ($session->check('keySearch')) {
				$session->delete('keySearch');
			}

			if ($session->check('hasfilter')) {
				$session->delete('hasfilter');
			}
			$query = $users;
		}

		try {
			//Sort
			$this->paginate = [
				'order' => [
					'Users.id' => 'DESC'
				],
				'sortableFields' => [
					'Users.id',
					'Users.username',
					'Users.email',
					'Users.phonenumber',
					'Users.address',
					'Users.point_user',
					'Roles.role_name'
				],
			];
			$this->set(compact('query', $this->paginate($query, ['limit' => PAGINATE_LIMIT])));
		} catch (NotFoundException $e) {
			$atribute = $this->request->getAttribute('paging');
			$requestedPage = $atribute['Users']['requestedPage'];
			$pageCount = $atribute['Users']['pageCount'];
			if ($requestedPage > $pageCount) {
				return $this->redirect("/admin/list-user?page=" . $pageCount . "");
			}
		}
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
			if (!($dataRole[0]['id'] == $atribute['role_id'] ||
				$dataRole[1]['id'] == $atribute['role_id'] ||
				$dataRole[2]['id'] == $atribute['role_id'])) {
				$this->Flash->error(__(ERROR_USER_DATA_CHANGED_NOT_ADD_CONFIRM));
				return $this->redirect(['action' => ADMIN_LIST_USERS]);
			}

			$dataUser = $this->{'CRUD'}->addUser($atribute);
			if ($dataUser['result'] == "invalid") {
				$error = $dataUser['data'];
				$this->set('error', $error);
				$data = $atribute;
			} else {
				// Checkmail trùng
				$checkmail = $this->{'Data'}->checkmail($atribute);
				if (count($checkmail) > 0) {
					$error['email'] = [EMAIL_ALREADY_EXISTS];
					$this->set('error', $error);
					$data = $atribute;
				} else {
					$hashPswdObj = new DefaultPasswordHasher;
					$dataUser['data']['password'] = $hashPswdObj->hash($dataUser['data']['password']);

					if ($dataUser['data']['password'] == '') {
						$dataUser['data']['password'] = '';
					}
					$this->Users->save($dataUser['data']);
					$this->Flash->success(__(SUCCESS_ADD_USER));
					return $this->redirect(['action' => ADMIN_LIST_USERS]);
				}
			}
			$this->set('dataUser', $data);
		}
		$this->set(compact('dataRole'));
	}

	//Edit Users
	public function editUser($id = null)
	{
		//Check URL_ID
		if (!is_numeric($id)) {
			$this->Flash->error(__(ERROR_USER_EMPTY));
			return $this->redirect(['action' => ADMIN_LIST_USERS]);
		} else {
			$checkUserID = $this->{'CRUD'}->checkIDUser($id);
			if (count($checkUserID) < 1) {
				$this->Flash->error(__(ERROR_USER_EMPTY));
				return $this->redirect(['action' => ADMIN_LIST_USERS]);
			}
		}

		$session = $this->request->getSession();
		$dataUser = $this->{'CRUD'}->getUserByID($id);
		$dataRole =  $this->{'CRUD'}->getAllRoles();

		if ($this->request->is('post')) {
			$atribute = $this->request->getData();
			//Check thay đổi
			if (
				trim($atribute['username']) == trim($checkUserID[0]['username']) &&
				trim($atribute['password']) == trim($checkUserID[0]['password']) &&
				trim($atribute['phonenumber']) == trim($checkUserID[0]['phonenumber']) &&
				trim($atribute['address']) == trim($checkUserID[0]['address']) &&
				$atribute['role_id'] == $checkUserID[0]['role_id']
			) {
				$this->Flash->error(__(ERROR_USER_NOT_CHANGED));
				$data = $atribute;
			} else {
				// Check dữ liệu F12
				if (!($dataRole[0]['id'] == h($atribute['role_id']) ||
					$dataRole[1]['id'] == h($atribute['role_id']) ||
					$dataRole[2]['id'] == h($atribute['role_id']))) {
					$this->Flash->error(__(ERROR_USER_DATA_CHANGED_NOT_UPDATED_CONFIRM));
					$data = $atribute;
				}

				if ($atribute['password'] == $dataUser[0]['password']) {
					$user = $this->Users->patchEntity($dataUser[0], h($atribute), ['validate' => 'Custom']);
					if ($user->hasErrors()) {
						$error = $user->getErrors();
						$this->set('error', $error);
						$data = $atribute;
					} else {
						$user->password = $atribute['password'];
						if ($this->Users->save($user)) {
							$this->Flash->success(__(SUCCESS_UPDATED_USER));
							return $this->redirect($atribute['referer']);
						}
					}
				} else {
					$user = $this->Users->patchEntity($dataUser[0], h($atribute));
					if ($user->hasErrors()) {
						$error = $user->getErrors();
						$this->set('error', $error);
						$data = $atribute;
					} else {
						$hashPswdObj = new DefaultPasswordHasher;
						$user->password = $hashPswdObj->hash($atribute['password']);
						if ($this->Users->save($user)) {
							$this->Flash->success(__(SUCCESS_UPDATED_USER));
							return $this->redirect($atribute['referer']);
						}
					}
				}
			}
		} else {
			$data = $dataUser[0];
			$data["referer"] = $this->referer();
			if ($data["referer"] == "/") {
				return $this->redirect(['action' => ADMIN_LIST_USERS]);
			}
		}
		$this->set(compact('dataRole'));
		$this->set('dataUser', $data);
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
			//Tự động logout khi khóa chính User đó
			$session = $this->request->getSession();
			if ($session->check('idUser')) {
				$idUsers = $session->read('idUser');
				$checkEmail = $this->{'Data'}->getCheckInfoUser($idUsers);
				if($checkEmail[0]['email'] == $dataUser[0]['email']){
					$session = $this->request->getSession();
					$session->destroy();
					return $this->redirect(Router::url(['_name' => NAME_LOGIN]));
				}else{
					$this->Flash->success(__(SUCCESS_USER_LOCK));
				}
			}else{
				$this->Flash->success(__(SUCCESS_USER_LOCK));
			}

		}else{
			$this->Flash->error(__(ERROR_USER_LOCK));
		}

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
			$this->Flash->success(__(SUCCESS_USER_UNLOCK));
			return $this->redirect("$urlPageList");
		}
		$this->Flash->error(__(ERROR_USER_LOCK));
	}

	//View user
	public function viewUser($id = null)
	{
		$dataUser = $this->{'CRUD'}->getUserByID($id);
		$dataRole =  $this->{'CRUD'}->getAllRoles();
		$this->set(compact('dataUser', 'dataRole'));
	}
}
