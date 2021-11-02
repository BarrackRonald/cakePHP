<?php

echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
$n = $this->request->getAttribute('paging')['Orderdetails']['start'];

?>

<div class="main-content container-fluid">
	<div class="page-title">
		<div class="row">
			<div class="col-12 col-md-6 order-md-1 order-last">
				<h3>Chi tiết đơn hàng</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-md-6 order-md-1 order-last">
				<?= $this->Flash->render() ?>
			</div>
		</div>
	</div>
	<section class="section">
		<div class="card">
			<div class="card-body">
				<table id="tbl-users-list" class='table table-striped' id="table1">
					<thead>
						<tr>
							<th>STT<th>
							<th>Tên sản phẩm</th>
							<th>Số lượng</th>
							<th>Point nhận được</th>
							<th>Giá sản phẩm</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($dataOrderDetails as $orderDetail) { ?>
							<tr>
								<td><?=$n++?><td>
								<td> <a><?= $orderDetail['Products']['product_name'] ?></a></td>
								<td> <a><?= $orderDetail['quantity_orderDetails'] ?></a></td>
								<td> <a><?= $orderDetail['point_orderDetail'].' point' ?></a></td>
								<td> <a><?= '$'.number_format($orderDetail['amount_orderDetails']) ?></a></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div style="margin-left: 25px">
				<a href="<?= $referer?>">
					<input type="submit" class="btn btn-info" value="Quay Lại" style="margin-bottom: 5px" />
				</a>
			</div>
		</div>
	</section>
</div>
<?php
echo $this->element('Admin/footer');
?>