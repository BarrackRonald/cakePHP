<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Admin Controller
 *
 * @method \App\Model\Entity\Admin[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AdminController extends AppController
{
	public function initialize(): void
	{
		parent::initialize();
		$this->loadComponent('Data');
		$this->loadComponent('CRUD');
		$this->loadModel("Categories");
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

	public function beforeFilter(EventInterface $event)
	{
		$session = $this->request->getSession();
		$flag = $session->read('flag');
		if (!$session->check('flag') || $flag == 1) {
			$this->Flash->error(__('Bạn không có quyền truy cập vào trang Admin.'));
			return $this->redirect(['controller' => 'NormalUsers', 'action' => 'index']);
		}
	}

	/**
	 * Index method
	 *
	 * @return \Cake\Http\Response|null|void Renders view
	 */
	public function index()
	{
		$Order = $this->{'CRUD'}->totalOrderForMonth();
		$User = $this->{'CRUD'}->totalUser();
		$product = $this->{'CRUD'}->totalProduct();
		$revenueOrderForMonth = $this->{'CRUD'}->revenueForMonth();
		$OrderForMonth = count($Order);
		$totalUser = count($User);
		$totalProduct = count($product);

		$this->set(compact('OrderForMonth', 'totalUser', 'totalProduct', 'revenueOrderForMonth'));
	}
}
