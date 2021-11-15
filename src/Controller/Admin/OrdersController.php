<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Routing\Router;

/**
 * Orders Controller
 *
 * @property \App\Model\Table\OrdersTable $Orders
 * @method \App\Model\Entity\Order[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrdersController extends AppController
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
		$this->loadModel("Orders");
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

	//List Order
	public function listOrders()
	{
		$orders = $this->{'CRUD'}->getAllOrder();
		$session = $this->request->getSession();
		//Search
		$key = $this->request->getQuery('key');
		if ($key) {
			//Lưu key
			$session->write('keySearch', trim($key));
			$query1 = $this->{'CRUD'}->getSearchOrder(trim($key));
			$querytoArr = $this->{'CRUD'}->getSearchOrderArr(trim($key));
			if (count($querytoArr) == 0) {
				$this->Flash->error(__(ERROR_SEARCH_NOT_FOUND));
			}
		} else {
			if ($session->check('keySearch')) {
				$session->delete('keySearch');
			}

			$query1 = $orders;
		}

		try {
			//Sort
			$this->paginate = [
				'order' => [
					'Orders.id' => 'DESC'
				],
				'sortableFields' => [
					'Orders.id',
					'Orders.order_name',
					'Orders.email',
					'Orders.phonenumber',
					'Orders.address',
					'Orders.total_point',
					'Orders.total_quantity',
					'Orders.total_amount',
					'Orders.status'
				],
			];
			$this->set(compact('query1', $this->paginate($query1, ['limit' => PAGINATE_LIMIT])));
		} catch (NotFoundException $e) {
			$atribute = $this->request->getAttribute('paging');
			$requestedPage = $atribute['Orders']['requestedPage'];
			$pageCount = $atribute['Orders']['pageCount'];
			if ($requestedPage > $pageCount) {
				return $this->redirect("/admin/list-orders?page=" . $pageCount . "");
			}
		}
	}

	//Chi tiết đơn hàng
	public function orderDetails($id = null)
	{
		//Check URL_ID
		if (!is_numeric($id)) {
			$this->Flash->error(__(ERROR_ORDER_EMPTY));
			return $this->redirect(['action' => ADMIN_LIST_ORDERS]);
		} else {
			$checkOrderID = $this->{'CRUD'}->getOrderByID($id);
			if (count($checkOrderID) < 1) {
				$this->Flash->error(__(ERROR_ORDER_EMPTY));
				return $this->redirect(['action' => ADMIN_LIST_ORDERS]);
			}
		}
		$dataOrderDetails = $this->{'CRUD'}->getOrderDetailsByID($id);
		$referer = $this->referer();
		$this->set('referer', $referer);
		if ($referer == "/") {
			return $this->redirect(['action' => ADMIN_LIST_ORDERS]);
		} else {
			$this->set(compact('dataOrderDetails', $this->paginate($dataOrderDetails, ['limit' => PAGINATE_LIMIT])));
		}
	}

	// Duyệt đơn hàng
	public function confirmOrder($id = null)
	{
		//Check URL_ID
		if (!is_numeric($id)) {
			$this->Flash->error(__(ERROR_ORDER_EMPTY));
			return $this->redirect(['action' => ADMIN_LIST_ORDERS]);
		} else {
			$checkOrderID = $this->{'CRUD'}->getOrderByID($id);
			if (count($checkOrderID) < 1) {
				$this->Flash->error(__(ERROR_ORDER_EMPTY));
				return $this->redirect(['action' => ADMIN_LIST_ORDERS]);
			}
		}

		$dataOrder = $this->{'CRUD'}->getOrderByID($id);
		$dataProducts = $this->{'CRUD'}->getAllProductArr();
		$idUser = $dataOrder[0]['user_id'];
		$dataUser = $this->{'CRUD'}->getUserByID($idUser);

		//Lấy dữ liệu Sản phẩm của Orders
		$dataProductForOrder = $this->{'CRUD'}->getOrderProductByID($id);

		if ($this->request->is('post')) {
			$atribute = $this->request->getData();

			// Check point sau khi duyệt đơn
			if ($atribute['status'] == $dataOrder[0]['status']) {
				$this->Flash->error(__(ERROR_ORDER_NOT_CHANGED));
				$data = $atribute;
			} else {
				//Check F12
				if ((
					($atribute['order_name'] != $dataOrder[0]['order_name'] ||
						$atribute['email'] != $dataOrder[0]['email'] ||
						$atribute['phonenumber'] != $dataOrder[0]['phonenumber'] ||
						$atribute['address'] != $dataOrder[0]['address'] ||
						$atribute['total_point'] != $dataOrder[0]['total_point'] ||
						$atribute['total_quantity'] != $dataOrder[0]['total_quantity'] ||
						$atribute['total_amount'] != $dataOrder[0]['total_amount']) ||
					($atribute['status'] != 0 &&
						$atribute['status'] != 1 &&
						$atribute['status'] != 2 &&
						$atribute['status'] != 3))) {
					$this->Flash->error(__(ERROR_ORDER_DATA_CHANGED_NOT_CONFIRM));
					$data = $atribute;
				} else {

					//Tính toán Số lượng sản phẩm, point của User khi confirm Order ở Admin
					foreach ($dataProducts as $product) {
						foreach ($dataProductForOrder[0]['orderdetails'] as $productOrder) {
							if($product['id'] == $productOrder['product_id']){
								switch ($atribute['status']) {
									case 0:
									case 1:
										if ($dataOrder[0]['status'] == 2 || $dataOrder[0]['status'] == 3) {
											$quantity = $product['quantity_product'] - $productOrder['quantity_orderDetails'];
											$pointAF = $dataUser[0]['point_user'] + $dataOrder[0]['total_point'];
										}else{
											$quantity = $product['quantity_product'];
											$pointAF = $dataUser[0]['point_user'];
										}
										break;
									case 2:
									case 3:
										if ($dataOrder[0]['status'] == 0 || $dataOrder[0]['status'] == 1) {
											$quantity = $product['quantity_product'] + $productOrder['quantity_orderDetails'];
											$pointAF = $dataUser[0]['point_user'] - $dataOrder[0]['total_point'];
										}else{
											$quantity = $product['quantity_product'];
											$pointAF = $dataUser[0]['point_user'];
										}
										break;
									default:
										break;
								}
								$this->{'Data'}->updateQuantity($quantity, $product['id']);
							}
						}
					}
					$this->{'Data'}->updatePoint($pointAF, $idUser);

					$confirm = $this->Orders->patchEntity($dataOrder[0], $this->request->getData());
					if ($confirm->hasErrors()) {
						$error = $confirm->getErrors();
						$this->set('error', $error);
						$data = $atribute;
					} else {
						if ($this->Orders->save($confirm)) {
							$this->Flash->success(__(SUCCESS_UPDATED_ORDER));
							return $this->redirect($atribute['referer']);
						}
					}
				}
			}
		} else {
			$data = $dataOrder[0];
			$data["Users"] = $dataUser[0];
			$data["referer"] = $this->referer();
			if ($data["referer"] == "/") {
				return $this->redirect(['action' => ADMIN_LIST_ORDERS]);
			}
		}

		$this->set('dataOrder', $data);
	}
}
