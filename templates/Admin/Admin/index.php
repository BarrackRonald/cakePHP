<?php

echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
$n = 1;
$i = 1;
$j = $this->request->getAttribute('paging')['Products']['start'];
?>

<div class="main-content container-fluid">
	<div class="page-title">
		<h3>Thống kê</h3>
		<p class="text-subtitle text-muted">Hiển thị thông tin thống kê của website</p>
	</div>
	<section class="section">
		<div class="row mb-2">
			<div class="col-12 col-md-3">
				<div class="card card-statistic">
					<div class="card-body p-0">
						<div class="d-flex flex-column">
							<div class='px-3 py-3 d-flex justify-content-between' style="justify-content: center;">
								<h3 class='card-title'>Tổng Đơn hàng năm <?= '20' . Date('y') ?></h3> <br>
							</div>
							<div style="margin: -15px 0 0 10px">
								<i></i>
							</div>
							<div class="card-right d-flex align-items-center" style="justify-content: center; margin-bottom: 15px">
								<p><?= number_format($OrderForYear) . ' đơn hàng' ?> </p>
							</div>
							<div class="chart-wrapper">
								<canvas id="canvas1" style="height:100px !important"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-12 col-md-3">
				<div class="card card-statistic">
					<div class="card-body p-0">
						<div class="d-flex flex-column">
							<div class='px-2 py-3 d-flex justify-content-between' style="justify-content: center;">
								<h3 class='card-title'>Tổng Đơn hàng của các năm</h3> <br>
							</div>
							<div style="margin: -15px 0 0 10px">
								<i></i>
							</div>
							<div class="card-right d-flex align-items-center" style="justify-content: center; margin-bottom: 15px">
								<p><?= number_format($totalOrders) . ' đơn hàng' ?> </p>
							</div>
							<div class="chart-wrapper">
								<canvas id="canvas2" style="height:100px !important"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-12 col-md-3">
				<div class="card card-statistic">
					<div class="card-body p-0">
						<div class="d-flex flex-column">
							<div class='px-3 py-3 d-flex justify-content-between' style="justify-content: center;">
								<h3 class='card-title'>Tổng Người dùng đặt hàng</h3> <br>
							</div>
							<div style="margin: -15px 0 0 10px">
								<i></i>
							</div>
							<div class="card-right d-flex align-items-center" style="justify-content: center; margin-bottom: 15px">
								<p><?= $totalUser . ' Users' ?> </p>
							</div>
							<div class="chart-wrapper">
								<canvas id="canvas3" style="height:100px !important"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-md-3">
				<div class="card card-statistic">
					<div class="card-body p-0">
						<div class="d-flex flex-column">
							<div class='px-3 py-3 d-flex justify-content-between' style="justify-content: center;">
								<h3 class='card-title'>Tổng Sản phẩm đang bán</h3> <br>
							</div>
							<div style="margin: -15px 0 0 10px">
								<i></i>
							</div>
							<div class="card-right d-flex align-items-center" style="justify-content: center; margin-bottom: 15px">
								<p><?= $totalProduct . ' sản phẩm' ?> </p>
							</div>
							<div class="chart-wrapper">
								<canvas id="canvas4" style="height:100px !important"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row mb-4">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header d-flex justify-content-between align-items-center">
						<h4 class="card-title">Sản phẩm hết hàng</h4>
						<div class="d-flex ">
							<a href="/admin/inventory/export">
								<i data-feather="download"></i>
							</a>
						</div>
					</div>
					<div class="card-body px-0 pb-0">
						<div class="table-responsive">
							<table class='table mb-0' id="table1">
								<thead>
									<tr>
										<th>STT</th>
										<th>Sản phẩm</th>
										<th>Danh mục</th>
										<th>Số lượng</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($getProductNoneQuantity as $product) { ?>
										<tr>
											<td><?= $j++ ?></td>
											<td><?= $product['product_name'] ?></td>
											<td><?= $product['Categories']['category_name'] ?></td>
											<td><?= $product['quantity_product'] ?></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div> <br>
					<div class="pagination-button">
						<?= $this->element('paginator') ?>
					</div>
				</div>
			</div>

		</div>

		<div class="row mb-4">
			<div class="col-md-6">
				<div class="card">
					<div class="card-header d-flex justify-content-between align-items-center">
						<h4 class="card-title">Top 5 sản phẩm bán chạy nhất trong tháng <?= Date('m') ?></h4>
					</div>
					<div class="card-body px-0 pb-0">
						<div class="table-responsive">
							<table class='table mb-0' id="table1">
								<thead>
									<tr>
										<th>Top</th>
										<th>Sản phẩm</th>
										<th>Số lượng bán ra</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($topSellMost as $product) { ?>
										<tr>
											<td><?= $n++ ?></td>
											<td><?= $product['Products']['product_name'] ?></td>
											<td><?= $product['totalQuantity'] ?></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card">
					<div class="card-header d-flex justify-content-between align-items-center">
						<h4 class="card-title">Top 5 sản phẩm bán chậm nhất trong tháng <?= Date('m') ?></h4>
					</div>
					<div class="card-body px-0 pb-0">
						<div class="table-responsive">
							<table class='table mb-0' id="table1">
								<thead>
									<tr>
										<th>Top</th>
										<th>Sản phẩm</th>
										<th>Số lượng bán ra</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($dataTopSellLeast as $product) { ?>
										<tr>
											<td><?= $i++ ?></td>
											<td><?= $product['product_name'] ?></td>
											<td><?= $product['totalQuantity'] ?></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row mb-4">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h3 class='card-heading p-1 pl-3'>Doanh Thu Năm <?= '20' . Date('y') ?> </h3>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-4 col-12">
								<div class="pl-3">
									<h1 class='mt-5'><?= 'Tổng Doanh thu: <br> $' . number_format($revenueOrder[0]['sum']) ?></h1>
									<p class='text-xs'><span class="text-green"><i data-feather="bar-chart" width="15"></i> <?= $percentGrowth ?>%</span> so với tháng trước</p>
									<div class="legends">
										<div class="legend d-flex flex-row align-items-center">
											<div class='w-3 h-3 rounded-full bg-info me-2'></div><span class='text-xs'>Tháng Trước</span>
										</div>
										<div class="legend d-flex flex-row align-items-center">
											<div class='w-3 h-3 rounded-full bg-blue me-2'></div><span class='text-xs'>Tháng Hiện Tại</span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-8 col-12">
								<canvas id="bar"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</section>
</div>


<?php
echo $this->element('Admin/footer');
?>
<script>
	// Biểu đồ thống kê tổng đơn hàng của các tháng trong năm
	lineChart1.data.datasets[0].data = [
		<?= $totalOrderForMonth['1'] ?>,
		<?= $totalOrderForMonth['2'] ?>,
		<?= $totalOrderForMonth['3'] ?>,
		<?= $totalOrderForMonth['4'] ?>,
		<?= $totalOrderForMonth['5'] ?>,
		<?= $totalOrderForMonth['6'] ?>,
		<?= $totalOrderForMonth['7'] ?>,
		<?= $totalOrderForMonth['8'] ?>,
		<?= $totalOrderForMonth['9'] ?>,
		<?= $totalOrderForMonth['10'] ?>,
		<?= $totalOrderForMonth['11'] ?>,
		<?= $totalOrderForMonth['12'] ?>
	];
	lineChart1.update();

	//Biểu đồ thống kê tổng đơn hàng của các năm (2021-2030)
	lineChart2.data.datasets[0].data = [
		<?= $totalOrderForYear['2021'] ?>,
		<?= $totalOrderForYear['2022'] ?>,
		<?= $totalOrderForYear['2023'] ?>,
		<?= $totalOrderForYear['2024'] ?>,
		<?= $totalOrderForYear['2025'] ?>,
		<?= $totalOrderForYear['2026'] ?>,
		<?= $totalOrderForYear['2027'] ?>,
		<?= $totalOrderForYear['2028'] ?>,
		<?= $totalOrderForYear['2029'] ?>,
		<?= $totalOrderForYear['2030'] ?>
	];
	lineChart2.update();

	//Biểu đồ thống kê người dùng đã đặt hàng của các tháng đã đặt hàng trong năm hiện tại
	lineChart3.data.datasets[0].data = [
		<?= $totalUserForMonths['1'] ?>,
		<?= $totalUserForMonths['2'] ?>,
		<?= $totalUserForMonths['3'] ?>,
		<?= $totalUserForMonths['4'] ?>,
		<?= $totalUserForMonths['5'] ?>,
		<?= $totalUserForMonths['6'] ?>,
		<?= $totalUserForMonths['7'] ?>,
		<?= $totalUserForMonths['8'] ?>,
		<?= $totalUserForMonths['9'] ?>,
		<?= $totalUserForMonths['10'] ?>,
		<?= $totalUserForMonths['11'] ?>,
		<?= $totalUserForMonths['12'] ?>
	];
	lineChart3.update();

	//Biểu đồ thống kê sản phẩm đã bán trong các tháng của năm hiện tại
	lineChart4.data.datasets[0].data = [
		<?= $totalProductsForMonths['1'] ?>,
		<?= $totalProductsForMonths['2'] ?>,
		<?= $totalProductsForMonths['3'] ?>,
		<?= $totalProductsForMonths['4'] ?>,
		<?= $totalProductsForMonths['5'] ?>,
		<?= $totalProductsForMonths['6'] ?>,
		<?= $totalProductsForMonths['7'] ?>,
		<?= $totalProductsForMonths['8'] ?>,
		<?= $totalProductsForMonths['9'] ?>,
		<?= $totalProductsForMonths['10'] ?>,
		<?= $totalProductsForMonths['11'] ?>,
		<?= $totalProductsForMonths['12'] ?>
	];
	lineChart4.update();

	//Biểu đồ thống kê doanh thu các tháng trong năm hiện tại
	myBar.data.datasets[0].data = [
		<?= $totalrevenueOrderForMonth['1'] ?>,
		<?= $totalrevenueOrderForMonth['2'] ?>,
		<?= $totalrevenueOrderForMonth['3'] ?>,
		<?= $totalrevenueOrderForMonth['4'] ?>,
		<?= $totalrevenueOrderForMonth['5'] ?>,
		<?= $totalrevenueOrderForMonth['6'] ?>,
		<?= $totalrevenueOrderForMonth['7'] ?>,
		<?= $totalrevenueOrderForMonth['8'] ?>,
		<?= $totalrevenueOrderForMonth['9'] ?>,
		<?= $totalrevenueOrderForMonth['10'] ?>,
		<?= $totalrevenueOrderForMonth['11'] ?>,
		<?= $totalrevenueOrderForMonth['12'] ?>
	];

	myBar.data.datasets[0].backgroundColor = [
		<?= $totalColorCurrentMonth['1'] ?>,
		<?= $totalColorCurrentMonth['2'] ?>,
		<?= $totalColorCurrentMonth['3'] ?>,
		<?= $totalColorCurrentMonth['4'] ?>,
		<?= $totalColorCurrentMonth['5'] ?>,
		<?= $totalColorCurrentMonth['6'] ?>,
		<?= $totalColorCurrentMonth['7'] ?>,
		<?= $totalColorCurrentMonth['8'] ?>,
		<?= $totalColorCurrentMonth['9'] ?>,
		<?= $totalColorCurrentMonth['10'] ?>,
		<?= $totalColorCurrentMonth['11'] ?>,
		<?= $totalColorCurrentMonth['12'] ?>
	];
	myBar.update();
</script>