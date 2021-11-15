<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Routing\Router;

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
			if ($month['Month'] == Date('m')) {
				$currentMonth = $month['sum'];
			} else {
				$lastMonth = $month['sum'];
			}
		}

		//Check rỗng tháng hiện tại và tháng trước
		if ($currentMonth == null && $lastMonth == null) {
			$currentMonth = 0;
			$lastMonth = 0;
		} elseif ($currentMonth == null) {
			$currentMonth = 0;
		} elseif ($lastMonth == null) {
			$lastMonth = 0;
		}

		//Tính % tăng trưởng của tháng hiện tại so với tháng trước
		$percent = (int) (($currentMonth - $lastMonth) / $lastMonth * 100);

		if ($percent > 0) {
			$percentGrowth = '+' . $percent;
		} else {
			$percentGrowth = $percent;
		}

		//Set màu cho cột
		$totalColorCurrentMonth = [
			1 => "chartColors.grey", 2 => "chartColors.grey", 3 => "chartColors.grey", 4 => "chartColors.grey", 5 => "chartColors.grey", 6 => "chartColors.grey",
			7 => "chartColors.grey", 8 => "chartColors.grey", 9 => "chartColors.grey", 10 => "chartColors.grey", 11 => "chartColors.grey", 12 => "chartColors.grey"
		];
		foreach ($revenueCurrentMonth as $month) {
			if ($month['Month'] == Date('m')) {
				$totalColorCurrentMonth[$month['Month']] = 'chartColors.blue';
			} else {
				$totalColorCurrentMonth[$month['Month']] = 'chartColors.info';
			}
		}

		//Top 5 sản phẩm bán ra nhiều nhất trong tháng này
		$topSellMost = $this->{'CRUD'}->topSellMost();

		//Top 5 sản phẩm bán chậm nhất trong tháng này
		$dataTopSellLeast = array();
		$dataProductsAtOrder = $this->{'CRUD'}->getProductsNotInOrder();
		foreach ($dataProductsAtOrder as $product) {
			array_push(
				$dataTopSellLeast,
				[
					'product_id' => $product['id'],
					'product_name' => $product['product_name'],
					'totalQuantity' => 0
				]
			);
		}

		$limit = 5 - count($dataProductsAtOrder);
		$topSellLeast = $this->{'CRUD'}->topSellLeast($limit);
		if ($limit != 0) {
			foreach ($topSellLeast as $product) {
				array_push(
					$dataTopSellLeast,
					[
						'product_id' => $product['product_id'],
						'product_name' => $product['Products']['product_name'],
						'totalQuantity' => $product['totalQuantity']
					]
				);
			}
		}

		//Sản phẩm đã hết hàng
		$getProductNoneQuantity = $this->{'CRUD'}->getProductNoneQuantity();
		try {
			$this->set(compact('getProductNoneQuantity', $this->paginate($getProductNoneQuantity, ['limit' => PAGINATE_LIMIT])));
		} catch (NotFoundException $e) {
			$atribute = $this->request->getAttribute('paging');
			$requestedPage = $atribute['Products']['requestedPage'];
			$pageCount = $atribute['Products']['pageCount'];
			if ($requestedPage > $pageCount) {
				return $this->redirect("/admin?page=" . $pageCount . "");
			}
		}

		$this->set(compact('dataTopSellLeast'));
		$this->set(compact('OrderForYear', 'totalOrders', 'topSellMost', 'totalUser', 'totalProduct', 'revenueOrder', 'totalOrderForMonth', 'totalOrderForYear', 'totalUserForMonths', 'totalProductsForMonths', 'totalrevenueOrderForMonth', 'totalColorCurrentMonth', 'percentGrowth'));
	}

	//Xuất file Excel sản phẩm hết hàng
	public function exportInventory()
	{
		$this->setResponse($this->getResponse()->withDownload('San-pham-het-hang.csv'));
		$data = $this->{'CRUD'}->getProductNoneQuantity();

		$header = ['Product ID', 'Tên Sản phẩm', 'Danh mục', 'Số lượng'];
		$extract = [
			'id',
			'product_name',
			function (array $row) {
				return $row['Categories']['category_name'];
			},
			'quantity_product'
		];

		//Xuất file không lỗi tiếng việt
		$csvEncoding = 'UTF-8';
		$bom = true;
		$newline = "\r\n";
		$eol = "\r\n";

		$this->set(compact('data'));
		$this->viewBuilder()
			->setClassName('CsvView.Csv')
			->setOptions([
				'serialize' => 'data',
				'header' => $header,
				'extract' => $extract,
				'csvEncoding' => $csvEncoding,
				'bom' => $bom,
				'newline' => $newline,
				'eol' => $eol,
			]);
	}
}
