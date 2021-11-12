<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Routing\Router;

/**
 * HistoryInput Controller
 *
 * @property \App\Model\Table\HistoryInputTable $HistoryInput
 * @method \App\Model\Entity\HistoryInput[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class HistoryInputController extends AppController
{
	public function initialize(): void
	{
		parent::initialize();
		$this->loadComponent('Data');
		$this->loadComponent('CRUD');
		$this->loadModel("HistoryInput");
		$this->loadModel("Products");
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

	//Nhập kho
	public function inputProduct($id = null)
	{
		$session = $this->request->getSession();
		$idUsers = $session->read('idUser');
		$dataUser = $this->{'Data'}->getInfoUser($idUsers);
		$products = $this->{'CRUD'}->getAllProduct();

		if($session->check('success')){
			$session->delete('success');
		}

		if ($this->request->is('post')) {
			$atribute = $this->request->getData();

			//Check F12
			$idProducts = $atribute['product_id'];
			$checkIDProducts = $this->{'CRUD'}->getProductByID($idProducts);
			if (count($checkIDProducts) < 1) {
				$this->Flash->error(__(ERROR_PRODUCT_DATA_CHANGED_NOT_CONFIRM));
				$data = $atribute;
			}else {
				$dataProduct = $this->{'CRUD'}->getProductByID($atribute['product_id']);
				if($atribute['quantity_product'] == ""){
					$error['quantity_product'] = [ERROR_NULL_QUANTITY];
					$this->set('error', $error);
					$data = $atribute;
				}else{
					//Cộng số lượng khi nhập vào
					$oldQuantity = $dataProduct[0]['quantity_product'];
					$inputQuantity = $atribute['quantity_product'];

					if (!is_numeric($inputQuantity)) {
						$error['quantity_product'] = [ERROR_NOT_STRING_QUANTITY];
						$this->set('error', $error);
						$data = $atribute;
					}else{
						//Giá trị hiện tại
						$atribute['quantity_product'] = $oldQuantity + $inputQuantity;
						$product = $this->Products->patchEntity($dataProduct[0], $atribute);

						if($product->hasErrors()){
							$error = $product->getErrors();
							$this->set('error', $error);
							$data = $atribute;
						}else{
							$result = $this->Products->save($product);
							if ($result) {
								$dataHistory = $this->{'CRUD'}->addInputHistory($dataUser, $result, $inputQuantity);
								if ($dataHistory['result'] == "invalid") {
									$error = $dataHistory['data'];
									$this->set('error', $error);
								} else {
									//Success
									$data = $atribute;
									$session->write('success', 1);
								}
							}else{
								$this->Flash->error(__(ERROR_INPUT_PRODUCT));
								$data = $atribute;
							}
						}

					}
				}

			}

			
		}else{
			$data = [];
			$data["referer"] = $this->referer();
			if ($data["referer"] == "/" || $data["referer"] == '/admin/input-product') {
				return $this->redirect(['action' => 'listInventory']);
			}
		}

		$this->set('dataProduct', $data);
		$this->set('product_id', $id);
		$this->set(compact('products'));
	}

	//Lịch sử nhập
	public function listHistory(){
		$session = $this->request->getSession();
		if($session->check('success')){
			$session->delete('success');
		}
		$historyInput = $this->{'CRUD'}->getAllHistoryInput();
		try {
			$this->set(compact('historyInput', $this->paginate($historyInput, ['limit' => PAGINATE_LIMIT])));
		} catch (NotFoundException $e) {
			$atribute = $this->request->getAttribute('paging');
			$requestedPage = $atribute['HistoryInput']['requestedPage'];
			$pageCount = $atribute['HistoryInput']['pageCount'];
			if ($requestedPage > $pageCount) {
				return $this->redirect("/admin/list-history?page=" . $pageCount . "");
			}
		}
	}

	//Danh sách hàng còn trong kho
	public function listInventory(){
		$session = $this->request->getSession();
		if($session->check('success')){
			$session->delete('success');
		}

		$products = $this->{'CRUD'}->getAllProduct();
		$session = $this->request->getSession();
		//Search
		$key = $this->request->getQuery('key');
		if ($key) {
			//Lưu key
			$session->write('keySearch', trim($key));
			$query = $this->{'CRUD'}->getSearch(trim($key));
			$querytoArr = $this->{'CRUD'}->getSearchtoArr(trim($key));
			if(count($querytoArr) == 0){
				$this->Flash->error(__(ERROR_SEARCH_NOT_FOUND));
			}
		} else {
			if ($session->check('keySearch')) {
				$session->delete('keySearch');
			}

			$query = $products;
		}

		//Pagination
		try {
			$this->set(compact('query', $this->paginate($query, ['limit' => PAGINATE_LIMIT])));
		} catch (NotFoundException $e) {
			$atribute = $this->request->getAttribute('paging');
			$requestedPage = $atribute['Products']['requestedPage'];
			$pageCount = $atribute['Products']['pageCount'];
			if ($requestedPage > $pageCount) {
				return $this->redirect("/admin/list-inventory?page=" . $pageCount . "");
			}
		}
	}

	//Export
	public function exportInventory()
	{
		$this->setResponse($this->getResponse()->withDownload('San-pham-ton-kho.csv'));
		$products = $this->{'CRUD'}->getAllProduct();
		$data = [
			['a', 'b', 'c'],
			[1, 2, 3],
			['you', 'and', 'me'],
		];

		$this->set(compact('data'));
		$this->viewBuilder()->setClassName('CsvView.Csv')->setOption('serialize', 'data');
	}

}
