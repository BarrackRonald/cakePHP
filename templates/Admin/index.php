<?php

echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
?>

<div class="main-content container-fluid">
	<div class="page-title">
		<h3>Dashboard</h3>
		<p class="text-subtitle text-muted">A good dashboard to display your statistics</p>
	</div>
	<section class="section">
		<div class="row mb-2">
			<div class="col-12 col-md-3">
				<div class="card card-statistic">
					<div class="card-body p-0">
						<div class="d-flex flex-column">
							<div class='px-3 py-3 d-flex justify-content-between' style="justify-content: center;">
								<h3 class='card-title'>Tổng Đơn hàng tháng <?= Date('m') ?></h3> <br>
							</div>
							<div style="margin: -15px 0 0 10px">
								<i>(*) Không tính đơn từ chối</i>
							</div>
							<div class="card-right d-flex align-items-center" style="justify-content: center; margin-bottom: 15px">
								<p><?= number_format($OrderForMonth) . ' đơn hàng' ?> </p>
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
							<div class='px-3 py-3 d-flex justify-content-between' style="justify-content: center;">
								<h3 class='card-title'>Tổng Người dùng hiện tại</h3> <br>
							</div>
							<div style="margin: -15px 0 0 10px">
								<i>(*) Không tính người dùng bị khóa</i>
							</div>
							<div class="card-right d-flex align-items-center" style="justify-content: center; margin-bottom: 15px">
								<p><?= $totalUser . ' Users' ?> </p>
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
								<h3 class='card-title'>Tổng Sản phẩm đang bán</h3> <br>
							</div>
							<div style="margin: -15px 0 0 10px">
								<i>*</i>
							</div>
							<div class="card-right d-flex align-items-center" style="justify-content: center; margin-bottom: 15px">
								<p><?= $totalProduct . ' sản phẩm' ?> </p>
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
								<h3 class='card-title'>Doanh thu tháng <?= Date('m') ?> </h3> <br>
							</div>
							<div style="margin: -15px 0 0 10px">
								<i>(*) Không tính đơn từ chối</i>
							</div>
							<div class="card-right d-flex align-items-center" style="justify-content: center; margin-bottom: 15px">
								<p><?= '$' . number_format($revenueOrderForMonth[0]['sum']) ?> </p>
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
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<h3 class='card-heading p-1 pl-3'>Sales</h3>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-4 col-12">
								<div class="pl-3">
									<h1 class='mt-5'>$21,102</h1>
									<p class='text-xs'><span class="text-green"><i data-feather="bar-chart" width="15"></i> +19%</span> than last month</p>
									<div class="legends">
										<div class="legend d-flex flex-row align-items-center">
											<div class='w-3 h-3 rounded-full bg-info me-2'></div><span class='text-xs'>Last Month</span>
										</div>
										<div class="legend d-flex flex-row align-items-center">
											<div class='w-3 h-3 rounded-full bg-blue me-2'></div><span class='text-xs'>Current Month</span>
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
			<div class="col-md-4">
				<div class="card ">
					<div class="card-header">
						<h4>Your Earnings</h4>
					</div>
					<div class="card-body">
						<div id="radialBars"></div>
						<div class="text-center mb-5">
							<h6>From last month</h6>
							<h1 class='text-green'>+$2,134</h1>
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
<!-- Biểu đồ thống kê -->
<script>
	lineChart1.data.datasets[0].data = [0, 0, 0, 0, 0, 0, 0, 0, 0, <?=$OrderForMonth?>, 0, 0];
	lineChart1.update();
</script>
