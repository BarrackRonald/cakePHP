<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Routing\Router;

/**
 * Categories Controller
 *
 * @property \App\Model\Table\CategoriesTable $Categories
 * @method \App\Model\Entity\Category[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CategoriesController extends AppController
{
	/**
	 * Index method
	 *
	 * @return \Cake\Http\Response|null|void Renders view
	 */
	public function initialize(): void
	{
		parent::initialize();
		$this->loadComponent('Data');
		$this->loadComponent('CRUD');
		$this->loadModel("Categories");
	}

	public function beforeFilter(EventInterface $event)
	{
		$session = $this->request->getSession();
		$flag = $session->read('flag');
		if (!$session->check('flag') || $flag == 1) {
			$this->Flash->error(__(ERROR_ROLE_ADMIN));
			return $this->redirect('/');
		} else {
			$idUser = $session->read('idUser');
			$check = $this->{'CRUD'}->checkUserLock($idUser);
			if (count($check) < 1) {
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

	//List Categories
	public function listCategories()
	{
		//Check Referer
		$session = $this->request->getSession();
		if ($session->check('error')) {
			$session->delete('error');
		}
		$categories = $this->{'CRUD'}->getAllCategory();

		try {
			$this->paginate = [
				'order' => ['id' => 'DESC'],
			];
			$this->set(compact('categories', $this->paginate($categories, ['limit' => PAGINATE_LIMIT])));
		} catch (NotFoundException $e) {
			$atribute = $this->request->getAttribute('paging');
			$requestedPage = $atribute['Categories']['requestedPage'];
			$pageCount = $atribute['Categories']['pageCount'];
			if ($requestedPage > $pageCount) {
				return $this->redirect("/admin/list-categories?page=" . $pageCount . "");
			}
		}
	}

	//Add Categories
	public function addCategory()
	{
		if ($this->request->is('post')) {
			$atribute = $this->request->getData();
			$dataCategory = $this->{'CRUD'}->addcategory($atribute);
			if ($dataCategory['result'] == "invalid") {
				$error = $dataCategory['data'];
				$this->set('error', $error);
				$data = $atribute;
				$this->set('dataCategory', $data);
			} else {
				$this->Flash->success(__(SUCCESS_ADD_CATEGORY));
				return $this->redirect(['action' => ADMIN_LIST_CATEGORIES]);
			}
		}
	}

	//Edit Categories
	public function editCategory($id = null)
	{
		//Check URL_ID
		if (!is_numeric($id)) {
			$this->Flash->error(__(ERROR_CATEGORY_EMPTY));
			return $this->redirect(['action' => ADMIN_LIST_CATEGORIES]);
		} else {
			$checkCategoryID = $this->{'CRUD'}->getCategoryByID($id);
			if (count($checkCategoryID) < 1) {
				$this->Flash->error(__(ERROR_CATEGORY_EMPTY));
				return $this->redirect(['action' => ADMIN_LIST_CATEGORIES]);
			}
		}

		$dataCategory = $this->{'CRUD'}->getCategoryByID($id);
		if ($this->request->is('post')) {
			$atribute = $this->request->getData();
			//Check thay đổi
			if (
				trim($atribute['category_name']) == trim($dataCategory[0]['category_name'])
			) {
				$this->Flash->error(__(ERROR_CATEGORY_NOT_CHANGED));
				$data = $atribute;
			} else {
				$category = $this->Categories->patchEntity($dataCategory[0], h($atribute));
				if ($category->hasErrors()) {
					$error = $category->getErrors();
					$this->set('error', $error);
					$data = $atribute;
				} else {
					if ($this->Categories->save($category)) {
						$this->Flash->success(__(SUCCESS_UPDATED_CATEGORY));
						return $this->redirect($atribute['referer']);
					} else {
						$this->Flash->error(__(ERROR_UPDATED_CATEGORY));
					}
				}
			}
		} else {
			$data = $dataCategory[0];
			$data["referer"] = $this->referer();
			if ($data["referer"] == "/") {
				return $this->redirect(['action' => ADMIN_LIST_CATEGORIES]);
			}
		}
		$this->set('dataCategory', $data);
	}

	//Delete Soft Categories
	public function deleteCategory($id = null)
	{
		$urlPageList = $_SERVER['HTTP_REFERER'];
		$this->request->allowMethod(['post', 'delete']);
		$dataCategory = $this->{'CRUD'}->getCategoryByID($id);
		$atribute = $this->request->getData();

		//Kiểm tra Danh mục còn sản phẩm không
		$checkProduct = $this->{'CRUD'}->checkProductByCategory($atribute);
		if (count($checkProduct) > 0) {
			$this->Flash->error(__(ERROR_CATEGORY_HAS_PRODUCT_NOT_DEL));
		} else {
			$atribute['del_flag'] = 1;
			$category = $this->Categories->patchEntity($dataCategory[0], $atribute);
			if ($this->Categories->save($category)) {
				$this->Flash->success(__(SUCCESS_DEL_CATEGORY));
			} else {
				$this->Flash->error(__(ERROR_DEL_CATEGORY));
			}
		}

		return $this->redirect("$urlPageList");
	}
}
