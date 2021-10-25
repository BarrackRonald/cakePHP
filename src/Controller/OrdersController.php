<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

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
			$this->Flash->error(__('Bạn không có quyền truy cập vào trang Admin.'));
			return $this->redirect(['controller' => 'NormalUsers', 'action' => 'index']);
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

	//List Products
	public function listOrders()
	{
		$orders = $this->{'CRUD'}->getAllOrder();
		//Search
		$key = $this->request->getQuery('key');
		if ($key) {
			if ($key == '') {
				$this->Flash->error(__('Không có dữ liệu Search!!!'));
			} else {
				$query1 = $this->{'CRUD'}->getSearchOrder($key);
			}
		} else {
			$query1 = $orders;
		}
		$this->set(compact('query1', $this->paginate($query1, ['limit' => '3'])));
	}

	//Chi tiết đơn hàng
	public function orderDetails($id = null)
	{
		$dataOrderDetails = $this->{'CRUD'}->getOrderDetailsByID($id);
		$this->set(compact('dataOrderDetails', $this->paginate($dataOrderDetails, ['limit' => '3'])));
	}

	// Duyệt đơn hàng
	public function confirmOrder($id = null)
	{

		//Check URL_ID
		if (!is_numeric($id)) {
			$this->Flash->error(__('Đơn hàng không tồn tại.'));
			return $this->redirect(['action' => 'listOrders']);
		} else {
			$checkOrderID = $this->{'CRUD'}->checkIDOrder($id);
			if (count($checkOrderID) < 1) {
				$this->Flash->error(__('Đơn hàng không tồn tại.'));
				return $this->redirect(['action' => 'listOrders']);
			}
		}

		$dataOrder = $this->{'CRUD'}->getOrderByID($id);
		$idUser = $dataOrder[0]['user_id'];
		$dataUser = $this->{'CRUD'}->getUserByID($idUser);

		if ($this->request->is('post')) {
			$atribute = $this->request->getData();

			// Check point sau khi duyệt đơn
			if ($atribute['status'] == $dataOrder[0]['status']) {
				$this->Flash->error(__('Đơn hàng không có sự thay đổi.'));
				$data = $atribute;
			} else {
				//Check F12
				if ((
					($atribute['username'] != $dataUser[0]['username']||
					$atribute['email'] != $dataUser[0]['email']||
					$atribute['phonenumber'] != $dataUser[0]['phonenumber']||
					$atribute['address'] != $dataUser[0]['address']||
					$atribute['total_point'] != $dataOrder[0]['total_point']||
					$atribute['total_quantity'] != $dataOrder[0]['total_quantity']||
					$atribute['total_amount'] != $dataOrder[0]['total_amount']) ||
					($atribute['status'] != 0 &&
					$atribute['status'] != 1 &&
					$atribute['status'] != 2))) {
					$this->Flash->error(__('Dữ liệu đã bị thay đổi. Không thể xác nhận Đơn hàng!!!'));
					return $this->redirect(['action' => 'listOrders']);
				}

				//Tính toán Xóa Point Khi Từ chối đơn
				if ($atribute['status'] == $dataOrder[0]['status']) {
					$pointAF = $dataUser[0]['point_user'];
				} else if ($atribute['status'] == 2) {
					$pointAF = $dataUser[0]['point_user'] - $dataOrder[0]['total_point'];
				} else if ($dataOrder[0]['status'] == 2) {
					$pointAF = $dataUser[0]['point_user'] + $dataOrder[0]['total_point'];
				} else {
					$pointAF = $dataUser[0]['point_user'];
				}
				$this->{'Data'}->updatePoint($pointAF, $idUser);

				$confirm = $this->Orders->patchEntity($dataOrder[0], $this->request->getData());
				if ($confirm->hasErrors()) {
					$error = $confirm->getErrors();
					$this->set('error', $error);
					$data = $atribute;
				} else {
					if ($this->Orders->save($confirm)) {
						$this->Flash->success(__('Đơn hàng đã được cập nhật thành công.'));
						return $this->redirect($atribute['referer']);
					}
				}
			}
		} else {
			$data = $dataOrder[0];
			$data["Users"] = $dataUser[0];
			$data["referer"] = $this->referer();
			if ($data["referer"] == "/") {
				return $this->redirect(['action' => 'listOrders']);
			}
		}
		$this->set('dataOrder', $data);
	}
}
