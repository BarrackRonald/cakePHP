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
		//Tổng đơn hàng đã bán trên website
		$totalOrder = $this->{'CRUD'}->totalOrder();
		$totalOrders = count($totalOrder);

		// Tổng đơn hàng trong năm hiện tại
		$Order = $this->{'CRUD'}->totalOrderForYear();
		$OrderForYear = count($Order);

		//Tổng đơn hàng hàng tháng
		$OrderAllMonth = $this->{'CRUD'}->totalOrderAllMonth();
		$totalOrderForMonth = [
			1 => "0", 2 => "0", 3 => "0", 4 => "0", 5 => "0", 6 => "0", 7 => "0", 8 => "0", 9 => "0", 10 => "0", 11 => "0", 12 => "0"
		];
		foreach ($OrderAllMonth as $month) {
			$totalOrderForMonth[$month['Month']] = $month['Count'];
		}

		//Tổng đơn hàng trong các năm gần đây (2021-2030)
		$OrderForYears = $this->{'CRUD'}->totalOrderAllYear();

		$totalOrderForYear = [
			2021 => "0", 2022 => "0", 2023 => "0", 2024 => "0", 2025 => "0", 2026 => "0", 2027 => "0", 2028 => "0", 2029 => "0", 2030 => "0"
		];
		foreach ($OrderForYears as $year) {
			$totalOrderForYear[$year['Year']] = $year['Count'];
		}

		//Tổng User đã đặt hàng
		$User = $this->{'CRUD'}->totalUser();
		$totalUser = count($User);

		//Tổng người dùng đã đặt hàng các tháng trong năm nay
		$totalUserForMonth = $this->{'CRUD'}->totalUserForMonth();
		$totalUserForMonths = [
			1 => "0", 2 => "0", 3 => "0", 4 => "0", 5 => "0", 6 => "0", 7 => "0", 8 => "0", 9 => "0", 10 => "0", 11 => "0", 12 => "0"
		];
		foreach ($totalUserForMonth as $month) {
			$totalUserForMonths[$month['Month']] = $month['Count'];
		}

		//Tổng sản phẩm đang bán
		$product = $this->{'CRUD'}->totalProduct();
		$totalProduct = count($product);

		//Tổng sản phẩm đã bán qua các tháng trong năm nay
		$productForMonth = $this->{'CRUD'}->totalProductForMonth();
		$totalProductsForMonths = [
			1 => "0", 2 => "0", 3 => "0", 4 => "0", 5 => "0", 6 => "0", 7 => "0", 8 => "0", 9 => "0", 10 => "0", 11 => "0", 12 => "0"
		];
		foreach ($productForMonth as $month) {
			$totalProductsForMonths[$month['Month']] = $month['Count'];
		}

		//Doanh thu
		$revenueOrder = $this->{'CRUD'}->revenue();

		//Doanh thu các tháng trong năm hiện tại
		$revenueOrderForMonth = $this->{'CRUD'}->revenueForMonth();
		$totalrevenueOrderForMonth = [
			1 => "0", 2 => "0", 3 => "0", 4 => "0", 5 => "0", 6 => "0", 7 => "0", 8 => "0", 9 => "0", 10 => "0", 11 => "0", 12 => "0"
		];
		foreach ($revenueOrderForMonth as $month) {
			$totalrevenueOrderForMonth[$month['Month']] = $month['sum'];
		}

		//Doanh thu tháng trước và tháng hiện tại
		$currentMonth = null;
		$lastMonth = null;
		$revenueCurrentMonth = $this->{'CRUD'}->revenueCurrentMonth();

		foreach ($revenueCurrentMonth as $month) {
			if($month['Month'] == Date('m')){
				$currentMonth = $month['sum'];
			}else{
				$lastMonth = $month['sum'];
			}
		}
		//check rỗng tháng hiện tại và thagns trước
		if($currentMonth == null && $lastMonth == null){
			$currentMonth = 0;
			$lastMonth = 0;
		}elseif($currentMonth == null){
			$currentMonth = 0;
		}elseif($lastMonth == null){
			$lastMonth = 0;
		}

		//Tính % tăng trưởng của tháng hiện tại so với tháng trước
		$percent = (int) (($currentMonth - $lastMonth)/$lastMonth * 100);

		if($percent > 0){
			$percentGrowth = '+'.$percent;

		}else{
			$percentGrowth = $percent;
		}

		//Set màu cho cột
		$totalColorCurrentMonth = [
			1 => "chartColors.grey", 2 => "chartColors.grey", 3 => "chartColors.grey", 4 => "chartColors.grey", 5 => "chartColors.grey", 6 => "chartColors.grey",
			7 => "chartColors.grey", 8 => "chartColors.grey", 9 => "chartColors.grey", 10 => "chartColors.grey", 11 => "chartColors.grey", 12 => "chartColors.grey"
		];
		foreach ($revenueCurrentMonth as $month) {
			if($month['Month'] == Date('m')){
				$totalColorCurrentMonth[$month['Month']] = 'chartColors.blue';
			}else{
				$totalColorCurrentMonth[$month['Month']] = 'chartColors.info';
			}
		}

		$this->set(compact('OrderForYear', 'totalOrders', 'totalUser', 'totalProduct', 'revenueOrder', 'totalOrderForMonth', 'totalOrderForYear', 'totalUserForMonths', 'totalProductsForMonths', 'totalrevenueOrderForMonth', 'totalColorCurrentMonth', 'percentGrowth'));
	}
}
